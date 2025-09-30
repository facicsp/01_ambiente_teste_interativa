<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);
$idAplicar = $seguranca->antisql($_REQUEST["idAplicar"]);

$sql = "SELECT * FROM aplicarprova WHERE idAplicarProva = '$idAplicar'";
$resultados = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($resultados);

if (mysqli_num_rows($resultados) == 1) {
  $data = mysql_result($resultados, 0, 'data');
  $idProva = mysql_result($resultados, 0, 'idProva');

  $result = mysqli_query($conexao, "SELECT descricao, a,b,c,d,e,f,g,h, resposta, correta FROM respostas 
    LEFT JOIN questao ON questao.idQuestao = respostas.idQuestao 
    LEFT JOIN aplicarprova ON aplicarprova.idProva = questao.idProva 
    WHERE respostas.idAluno = '$idAluno' AND aplicarprova.idAplicarProva = '$idAplicar'");

  if (mysqli_num_rows($result) == 0) echo json_encode(false);

  while($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  echo json_encode($rows);
} else {
  echo json_encode(false);
}