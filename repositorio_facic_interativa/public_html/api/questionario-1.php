<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);
$idDisciplina = $seguranca->antisql($_REQUEST["idDisciplina"]);

$dados = [];

// questionário normal
$sql = "SELECT * FROM aplicarProva WHERE '".date("Y-m-d", time())." 23:59:59' >= abertura AND idProfessor != '' AND idDisciplina = '$idDisciplina' AND bimestre NOT IN (10,20)";
$resultados = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($resultados);

if ($linhas > 0) {
  for ($i = 0; $i < $linhas; $i++) {
    $idApl  = mysql_result($resultados, $i, "idAplicarProva");
    $idProva= mysql_result($resultados, $i, "idProva");
    $titulo = mysql_result($resultados, $i, "titulo");
    $data   = mysql_result($resultados, $i, "fechamento");

    $result = mysqli_query($conexao, "SELECT idAluno FROM respostas 
      LEFT JOIN questao ON questao.idQuestao = respostas.idQuestao 
      LEFT JOIN aplicarprova ON aplicarprova.idProva = questao.idProva 
      WHERE respostas.idAluno = '$idAluno' AND aplicarprova.idAplicarProva = '$idApl'");

    $status = "Responder";

    if (mysqli_num_rows($result) > 0) $status = "Ver resultado";
    else if (strtotime(date("Y-m-d")) > strtotime($data)) $status = "Prazo excedido";

    $date = date_format(date_create($data),"d/m/Y");

    $dados[] = [
      "idApl"  => $idApl,
      "titulo" => $titulo,
      "status" => $status,
      "date"   => $date,
      "idProva"=> $idProva,
      "tipo"   => "pergunta"
    ];
  }
}

// questionário p1 p2
$sql = "SELECT * FROM aplicarProva WHERE '".date("Y-m-d", time())." 23:59:59' >= abertura AND idProfessor != '' AND idDisciplina = '$idDisciplina' AND bimestre IN (10,20)";
$resultados = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($resultados);

if ($linhas > 0) {
    for ($i = 0; $i < $linhas; $i++) {

        $idApl  = mysql_result($resultados, $i, "idAplicarProva");
        $idProva= mysql_result($resultados, $i, "idProva");
        $titulo = mysql_result($resultados, $i, "titulo");
        $data   = mysql_result($resultados, $i, "fechamento");
        $bim    = mysql_result($resultados, $i, "bimestre");

        $result = mysqli_query($conexao, "SELECT idaluno FROM lista_resposta 
          WHERE idaluno = '$idAluno' AND idprova = '$idProva'");

        $status = "Responder"; 

        if (mysqli_num_rows($result) > 0) $status = "Ver resultado";
        else if (strtotime(date("Y-m-d")) > strtotime($data)) $status = "Prazo excedido";

        $date = date_format(date_create($data),"d/m/Y");
        
        $dados[] = [
          "idApl"  => $idApl,
          "titulo" => $titulo,
          "status" => $status,
          "date"   => $date,
          "idProva"=> $idProva,
          "tipo"   => "prova"
        ];
    }
}

echo json_encode($dados);