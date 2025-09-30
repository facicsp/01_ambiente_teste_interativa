<?php

session_start();

if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "professor") {

  $data = json_decode(file_get_contents("php://input"), true);
  
  include 'LoginRestrito/conexao.php';

  $seguranca    = new Seguranca();
  $idProfessor  = $_SESSION["id"];
  $titulo       = $seguranca->antisql($data["titulo"]);
  $descricao    = $seguranca->antisql($data["descricao"]);
  $correta      = $seguranca->antisql($data["correta"]);
  $idProva      = $seguranca->antisql($data["idProva"]);

  if (isset($_SESSION['idProva']) && $idProva) {
    $idProva = $_SESSION['idProva'];
  } else {
    mysqli_query($conexao, "INSERT INTO prova VALUES (NULL, '$titulo', '$idProfessor')");
    $result  = mysqli_query($conexao, "SELECT idProva FROM prova WHERE titulo = '$titulo' AND idProfessor = '$idProfessor' ORDER BY idProva DESC LIMIT 1");
    $idProva = mysql_result($result, 0, 'idProva');
    $_SESSION['idProva'] = $idProva;
  }

  $a = $data["0"] ? $seguranca->antisql($data["0"]) : NULL;
  $b = $data["1"] ? $seguranca->antisql($data["1"]) : NULL;
  $c = $data["2"] ? $seguranca->antisql($data["2"]) : NULL;
  $d = $data["3"] ? $seguranca->antisql($data["3"]) : NULL;
  $e = $data["4"] ? $seguranca->antisql($data["4"]) : NULL;
  $f = $data["5"] ? $seguranca->antisql($data["5"]) : NULL;
  $g = $data["6"] ? $seguranca->antisql($data["6"]) : NULL;
  $h = $data["7"] ? $seguranca->antisql($data["7"]) : NULL;

  mysqli_query($conexao, "INSERT INTO questao VALUES(NULL, '$descricao', '$a', '$b', '$c', '$d', '$e', '$f', '$g', '$h', '$correta', '$idProva')");
  echo json_encode($idProva);
} else {  
  echo json_encode(false);
} 