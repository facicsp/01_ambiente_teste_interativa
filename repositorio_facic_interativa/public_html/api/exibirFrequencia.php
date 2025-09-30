<?php

include 'hasAccess.php';
include 'util/mime_content_type.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

$sql = "select acesso from usuario where idUsuario = '$idAluno'";

$result = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($result);

if ($linhas > 0) 
  echo json_encode(mysql_result($result, 0, "acesso"));
else 
  echo json_encode(0);

exit;