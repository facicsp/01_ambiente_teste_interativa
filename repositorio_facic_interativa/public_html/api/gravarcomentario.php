<?php

include 'hasAccess.php';

$seguranca  = new Seguranca();
$idUsuario  = $seguranca->antisql($_POST["id"]);;
$comentario = $seguranca->antisql($_POST["resposta"]);
$tipo       = 'aluno';
$idTopico   = $seguranca->antisql($_POST["idTopico"]);

$sql = "INSERT INTO comentario VALUES(null,'$comentario','$idUsuario','$tipo','$idTopico',DEFAULT)";
mysql_query($sql);

$result = mysql_query("SELECT idComentario FROM comentario 
  WHERE comentario = '$comentario' 
  AND idUsuario = '$idUsuario' 
  AND idTopico = '$idTopico' 
  ORDER BY idComentario DESC 
  LIMIT 1");

if (mysql_num_rows($result) > 0) {

  $idComentario = mysql_result($result, 0, "idComentario");
  echo json_encode($idComentario);

} else echo json_encode(false);