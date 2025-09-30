<?php

session_start();

include "LoginRestrito/conexao.php";
$seguranca = new Seguranca();

$_SESSION['semestre'] = $seguranca->antisql($_GET["semestre"]);

echo "<script>location.href='index.php';</script>";