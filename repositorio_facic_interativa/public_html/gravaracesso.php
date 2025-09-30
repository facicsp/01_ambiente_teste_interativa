<?php
session_start();
if($_SESSION["tipo"] == "aluno"){
$idAluno = $_SESSION["id"];
include 'LoginRestrito/conexao.php';

$sql = "SELECT * FROM acesso WHERE idusuario = '$idAluno' ORDER BY idacesso DESC limit 1";
$result = mysqli_query($conexao, $sql);
$idacesso = mysql_result($result, 0, "idacesso");
date_default_timezone_set('America/Sao_Paulo');
$hora = date("H:i");
$sql = "UPDATE acesso SET horasaida = '$hora' WHERE idacesso = '$idacesso'";
mysqli_query($conexao, $sql);

}
?>