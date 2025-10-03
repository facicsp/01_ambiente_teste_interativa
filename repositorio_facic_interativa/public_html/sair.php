<?php

session_start();
if ($_SESSION["tipo"] == "professor") {
    include './conexao.php';
    $idProfessor = $_SESSION["id"];
    $sql = "SELECT * FROM acessoprofessor WHERE idProfessor= '$idProfessor' ORDER BY idacesso DESC limit 1";
    $result = mysql_query($sql);
    $idacesso = mysql_result($result, 0, "idacesso");
    date_default_timezone_set('America/Sao_Paulo');
    $hora = date("H:i");
    $sql = "UPDATE acessoprofessor SET horasaida = '$hora' WHERE idacesso = '$idacesso'";
    mysql_query($sql);
    //echo $sql;
}
if ($_SESSION["tipo"] == "aluno") {
    include './conexao.php';
    $idAluno = $_SESSION["id"];
    $sql = "SELECT * FROM acesso WHERE idusuario= '$idAluno' ORDER BY idacesso DESC limit 1";
    //echo "<p>$sql</p>";
    $result = mysql_query($sql);
    $idacesso = mysql_result($result, 0, "idacesso");
    date_default_timezone_set('America/Sao_Paulo');
    $hora = date("H:i");
    $sql = "UPDATE acesso SET horasaida = '$hora' WHERE idacesso = '$idacesso'";
    mysql_query($sql);
    //echo "<p>$sql</p>";
}

session_destroy();

$inativo = "";
if ($_GET["redirect"]) $inativo = "alert('Desconectado por inatividade');";

echo "<script>$inativo window.location='login.html';</script>";
?>
