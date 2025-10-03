<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_POST["id"]);
$idProfessor = $seguranca->antisql($_POST["professor"]);
$assunto = $seguranca->antisql($_POST["assunto"]);
$mensagem = $seguranca->antisql($_POST["mensagem"]);
$tipo = "aluno";

date_default_timezone_set('America/Sao_Paulo');
$data = Date("Y-m-d");

$sql = "show table status like 'contato'";
$result = mysql_query($sql);
$idContato = mysql_result($result, 0, "Auto_increment");


$sql = "INSERT INTO contato VALUES('$idContato','$assunto','$idAluno','$idProfessor')";
mysql_query($sql);
$sql = "INSERT INTO mensagem VALUES(null,'$mensagem','$data','$tipo','nao','$idContato')";
mysql_query($sql);

echo json_encode(true);