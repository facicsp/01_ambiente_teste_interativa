<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_POST["id"]);
$respostas = $_POST["respostas"];

for ($i=0; $i<sizeof($respostas); $i++) {
  $idQuestao = $respostas[$i]["idQuestao"];
  $resposta  = $respostas[$i]["resposta"];

  mysqli_query($conexao, "INSERT INTO respostas VALUES (NULL, '$idQuestao', '$resposta', '$idAluno')");
}

echo json_encode(true);