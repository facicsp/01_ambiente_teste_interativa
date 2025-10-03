<?php

session_start();

include "conexao.php";
$seguranca = new Seguranca();

$_SESSION['semestre'] = $seguranca->antisql($_GET["semestre"]);

echo "<script>location.href='index.php';</script>";