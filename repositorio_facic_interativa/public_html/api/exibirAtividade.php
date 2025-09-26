<?php

include 'hasAccess.php';
include 'util/mime_content_type.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);
$idAula = $seguranca->antisql($_REQUEST["idAula"]);

$sql = "SELECT idAtividade, titulo, arquivo FROM atividade WHERE idAula = '$idAula' AND idAluno = '$idAluno'";
$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if ($linhas > 0) {
  while($row = mysql_fetch_assoc($result)) {
    $row["type"] = getMimeType($row["arquivo"]);
    $rows[] = $row;
  }
  echo json_encode($rows);

} else exit(json_encode([]));