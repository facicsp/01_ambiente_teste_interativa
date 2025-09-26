<?php

session_start();

$i = $_GET["id"];

$_SESSION["id"]      = $_SESSION["coordenados"][$i]["idProfessor"];
$_SESSION["usuario"] = $_SESSION["coordenados"][$i]["email"];
$_SESSION["tipo"]    = "professor";
$nome = $_SESSION["coordenados"][$i]["nome"];

$_SESSION["coordenados"] = false;
$_SESSION["coordenando"] = true;

// var_dump($_SESSION);
// exit;

echo "<script>alert('Alterado para o perfil do professor $nome'); location.href='index.php';</script>";