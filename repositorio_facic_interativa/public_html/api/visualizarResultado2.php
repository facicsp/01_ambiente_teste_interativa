<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);
$idAplicar = $seguranca->antisql($_REQUEST["idAplicar"]);

$sql = "SELECT * FROM aplicarProva WHERE idAplicarProva = '$idAplicar'";
$resultados = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($resultados);

if (mysqli_num_rows($resultados) == 1) {
  $data = mysql_result($resultados, 0, 'data');
  $idProva = mysql_result($resultados, 0, 'idProva');

  $result = mysqli_query($conexao, "SELECT * FROM lista_resposta 
    LEFT JOIN questao2 ON questao2.idQuestao = lista_resposta.idQuestao 
    WHERE lista_resposta.idprova = '$idProva' AND idaluno = '$idAluno'");


  if (mysqli_num_rows($result) == 0) exit(json_encode(false));

  $dados = [];
  $nota  = 0;

  for ($i=0; $i<mysqli_num_rows($result); $i++) {
    $descricao = mysql_result($result, $i, 'descricao');
    $resposta  = mysql_result($result, $i, 'resposta' );
    $correcao  = mysql_result($result, $i, 'correcao');
    $idQuestao = mysql_result($result, $i, 'idQuestao');
    $tipo      = mysql_result($result, $i, 'tipo');

    $retorno = is_numeric($correcao) ? ($correcao > 0 ? "acertou + $correcao" : "errou") : "aguardando correção";
    $nota += is_numeric($correcao) ? $correcao : 0;

    $dados[$i] = [
      "descricao" => $descricao,
      "retorno"   => $retorno
    ];

    if ($tipo == "dissertativa") {
        $dados[$i]["tipo"] = "dissertativa";
        $dados[$i]["resposta"] = $resposta;
    } else {
        $dados[$i]["tipo"] = "objetiva";
        $dados[$i]["alternativas"] = [];

        $resultAlt = mysqli_query($conexao, "SELECT * FROM alternativa WHERE idQuestao = '$idQuestao'");
        
        for ($j=0; $j<mysqli_num_rows($resultAlt); $j++) { 
            $idalternativa = mysql_result($resultAlt, $j, 'idalternativa');
            $alternativa   = mysql_result($resultAlt, $j, 'alternativa');
            $correta       = mysql_result($resultAlt, $j, 'correta');

            $dados[$i]["alternativas"][$j] = [
                "idalternativa" => $idalternativa,
                "alternativa"   => $alternativa,
                "cor" => ($correta == 'sim' ? 'rgba(0, 128, 0, .2)' : ($resposta == $idalternativa ? 'rgba(255, 0, 0, .2)' : ''))
            ];
        }
    }
  }

  echo json_encode([
    "questoes" => $dados,
    "nota"     => $nota
  ]);

} else {
  echo json_encode([]);
}