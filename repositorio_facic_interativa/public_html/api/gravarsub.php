<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idUsuario = $seguranca->antisql($_POST["id"]);
$sub       = $seguranca->antisql($_POST["resposta"]);
$tipo      = 'aluno';
$idComent  = $seguranca->antisql($_POST["idComentario"]);
$idTopico  = $seguranca->antisql($_POST["idTopico"]);

$sql = "INSERT INTO subresposta VALUES(null,'$sub','$idUsuario','$tipo','$idComent',DEFAULT)";
mysql_query($sql);

echo json_encode(true);