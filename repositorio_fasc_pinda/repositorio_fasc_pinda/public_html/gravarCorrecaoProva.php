<?php

session_start(); 
include 'LoginRestrito/conexao.php';

if (isset($_SESSION["usuario"])) {
  if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
    $seguranca = new Seguranca();

    $id = $seguranca->antisql($_REQUEST["id"]);
    $nota = $seguranca->antisql($_REQUEST["nota"]);

    mysqli_query($conexao, "UPDATE lista_resposta SET correcao = '$nota' WHERE idlistaresposta = '$id'");

    exit(true);
  }
}

exit(false);