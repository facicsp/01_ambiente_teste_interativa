<?php
session_start();
if($_SESSION["tipo"] == "aluno"){
$idAluno = $_SESSION["id"];
include './conexao.php';

$sql = "SELECT * FROM acesso WHERE idusuario = '$idAluno' ORDER BY idacesso DESC limit 1";
$result = mysql_query($sql);
$idacesso = mysql_result($result, 0, "idacesso");
date_default_timezone_set('America/Sao_Paulo');
$hora = date("H:i");
$sql = "UPDATE acesso SET horasaida = '$hora' WHERE idacesso = '$idacesso'";
mysql_query($sql);

}
?>