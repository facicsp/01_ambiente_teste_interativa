<?php

session_start(); 
include './conexao.php';

if (isset($_SESSION["usuario"])) {
  if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
    $seguranca = new Seguranca();

    $id = $seguranca->antisql($_REQUEST["id"]);
    $nota = $seguranca->antisql($_REQUEST["nota"]);

    mysql_query("UPDATE lista_resposta SET correcao = '$nota' WHERE idlistaresposta = '$id'");

    exit(true);
  }
}

exit(false);