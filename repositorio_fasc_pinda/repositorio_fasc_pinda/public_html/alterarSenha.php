<?php

session_start();

include "topo.php";
include "LoginRestrito/conexao.php";

$seguranca    = new Seguranca();

$idProfessor = $seguranca->antisql($_POST["txtProfessor"]);
$senhaAtual = md5($seguranca->antisql($_POST["txtSenha"]));
$novaSenha  = md5($_POST["txtNovaSenha"]);

mysqli_query($conexao, "UPDATE professor SET senha = '$novaSenha' WHERE idProfessor = '$idProfessor' AND senha = '$senhaAtual'");

echo "<script>alert('Senha alterada com sucesso!'); location.href='index.php';</script>";