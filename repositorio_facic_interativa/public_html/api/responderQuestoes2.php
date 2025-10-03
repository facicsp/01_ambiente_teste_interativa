<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);
$idAplicar = $seguranca->antisql($_REQUEST["idAplicar"]);

$sql = "SELECT * FROM aplicarprova WHERE idAplicarProva = '$idAplicar'";
$resultados = mysql_query($sql);

$dados = [];

if (mysql_num_rows($resultados) == 1) {
  $data = mysql_result($resultados, 0, 'fechamento');
  $idProva = mysql_result($resultados, 0, 'idProva');
  
  $result = mysql_query("SELECT * FROM questao2 WHERE idProva = '$idProva' ORDER BY RAND()");

  for ($i=0; $i<mysql_num_rows($result); $i++) {

    $idQuestao = mysql_result($result, $i, 'idQuestao');
    $descricao = mysql_result($result, $i, 'descricao');
    $tipo      = mysql_result($result, $i, 'tipo');

    $dados[$i] = [
        "idQuestao" => $idQuestao,
        "descricao" => $descricao,
        "tipo"      => $tipo,
    ];

    if ($tipo === "objetiva") {
      $dados[$i]["alternativas"] = [];

      $resultAlt = mysql_query("SELECT * FROM alternativa WHERE idQuestao = '$idQuestao'");

      for ($iAlt=0; $iAlt < mysql_num_rows($resultAlt); $iAlt++) { 
        $idAlternativa = mysql_result($resultAlt, $iAlt, "idalternativa");
        $alternativa   = mysql_result($resultAlt, $iAlt, "alternativa");
        
        $dados[$i]["alternativas"][] = [
          "idAlternativa" => $idAlternativa,
          "alternativa"   => $alternativa
        ];
      }
    }
  }

  echo json_encode($dados);

} else echo json_encode(false);