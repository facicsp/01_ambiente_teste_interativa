<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idContato = $seguranca->antisql($_REQUEST["idContato"]);

$sql = "SELECT *,DATE_FORMAT(data, '%d/%m/%Y') AS dataMensagem FROM mensagem WHERE idcontato = '$idContato' ORDER BY idMensagem DESC";
$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if ($linhas > 0) {
  while($row = mysql_fetch_assoc($result)) {
    $tipo = ucwords($row["tipo"]);
    $status = $row["status"];
    $idMensagem = $row["idMensagem"];

    $rows[] = $row;

    if($tipo != ucwords("aluno") && $status == "nao") {
      $sqlMensagem = "UPDATE mensagem SET status = 'sim' WHERE idMensagem = '$idMensagem'";
      mysql_query($sqlMensagem);
    }
  }

  echo json_encode($rows);
} else {
  echo json_encode([]);
}