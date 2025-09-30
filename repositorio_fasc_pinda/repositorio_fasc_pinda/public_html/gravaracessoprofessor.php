<?php
session_start();
if($_SESSION["tipo"] == "professor"){
$idProfessor = $_SESSION["id"];
include 'LoginRestrito/conexao.php';

$sql = "SELECT * FROM acessoprofessor WHERE idProfessor= '$idProfessor' ORDER BY idacesso DESC limit 1";
$result = mysqli_query($conexao, $sql);
$idacesso = mysql_result($result, 0, "idacesso");
date_default_timezone_set('America/Sao_Paulo');
$hora = date("H:i");
$sql = "UPDATE acessoprofessor SET horasaida = '$hora' WHERE idacesso = '$idacesso'";
mysqli_query($conexao, $sql);
//echo $sql;
}
?>