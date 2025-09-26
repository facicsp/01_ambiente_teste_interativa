<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_POST["id"]);
$mensagem = $seguranca->antisql($_POST["mensagem"]);
$idContato = $seguranca->antisql($_POST["idContato"]);
$tipo = "aluno";

date_default_timezone_set('America/Sao_Paulo');
$data = Date("Y-m-d");

$sql = "INSERT INTO mensagem VALUES(null,'$mensagem','$data','$tipo','nao','$idContato')";
mysql_query($sql);

echo json_encode(true);