<?php
session_start();
if($_SESSION["tipo"] == "professor"){
$idProfessor = $_SESSION["id"];
include './conexao.php';

$sql = "SELECT * FROM acessoprofessor WHERE idProfessor= '$idProfessor' ORDER BY idacesso DESC limit 1";
$result = mysql_query($sql);
$idacesso = mysql_result($result, 0, "idacesso");
date_default_timezone_set('America/Sao_Paulo');
$hora = date("H:i");
$sql = "UPDATE acessoprofessor SET horasaida = '$hora' WHERE idacesso = '$idacesso'";
mysql_query($sql);
//echo $sql;
}
?>