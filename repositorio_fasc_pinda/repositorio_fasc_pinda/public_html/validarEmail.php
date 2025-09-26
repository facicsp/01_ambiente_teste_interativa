<?php
session_start();
include "conexao.php";
$seguranca = new Seguranca();
$usuario = $seguranca->antisql($_POST["txtEmail"]);

    $sql = "SELECT idUsuario FROM usuario "
        . "WHERE email='$usuario' "
        . "AND tipo='aluno'";
$resultados = mysql_query($sql);
$linhas = mysql_num_rows($resultados);
if($linhas > 0){
    $_SESSION["idUsuario"] = mysql_result($resultados, 0, "idUsuario");
    $_SESSION["email"]=$usuario;
    
echo "<script>window.location='mail/enviarEmail.php';</script>";
}else{
echo "<script>alert('Dados incorretos.');"
    . "window.location='login.html';</script>";

}



?>