<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);
$idAplicar = $seguranca->antisql($_REQUEST["idAplicar"]);

$sql = "SELECT * FROM aplicarprova WHERE idAplicarProva = '$idAplicar'";
$resultados = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($resultados);

if (mysqli_num_rows($resultados) == 1) {
  $data = mysql_result($resultados, 0, 'fechamento');
  $idProva = mysql_result($resultados, 0, 'idProva');

  $result = mysqli_query($conexao, "SELECT idAluno FROM respostas 
    LEFT JOIN questao ON questao.idQuestao = respostas.idQuestao 
    LEFT JOIN aplicarprova ON aplicarprova.idProva = questao.idProva 
    WHERE respostas.idAluno = '$idAluno' AND aplicarprova.idAplicarProva = '$idAplicar'");

  if (mysqli_num_rows($result) > 0 || strtotime(date("Y-m-d")) > strtotime($data)) exit(json_encode(false));

  $result = mysqli_query($conexao, "SELECT * FROM questao WHERE idProva = '$idProva' ORDER BY RAND() LIMIT 10");
  
  while($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  echo json_encode($rows);

} else echo json_encode(false);
