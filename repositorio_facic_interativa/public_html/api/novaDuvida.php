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
$result = mysqli_query($conexao, $sql);
$idContato = mysql_result($result, 0, "Auto_increment");


$sql = "INSERT INTO contato VALUES('$idContato','$assunto','$idAluno','$idProfessor')";
mysqli_query($conexao, $sql);
$sql = "INSERT INTO mensagem VALUES(null,'$mensagem','$data','$tipo','nao','$idContato')";
mysqli_query($conexao, $sql);

echo json_encode(true);