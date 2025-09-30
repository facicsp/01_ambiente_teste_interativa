<?php

session_start();

if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "professor") {
      
  include "LoginRestrito/conexao.php";
  $seguranca = new Seguranca();
  
  $idBoletim = $seguranca->antisql($_POST["idBoletim"]);
  $idDisciplina = $seguranca->antisql($_POST["idDisciplina"]);
  $bimestre1 = $seguranca->antisql($_POST["txtBimestre1"]);
  $bimestre2 = $seguranca->antisql($_POST["txtBimestre2"]);
  $exame = $seguranca->antisql($_POST["txtExame"]);
  $sub = $seguranca->antisql($_POST["txtSub"]);

//   $bimestre1 = round($bimestre1 / 0.5, 0) * 0.5;
//   $bimestre2 = round($bimestre2 / 0.5, 0) * 0.5;
//   $exame = round($exame / 0.5, 0) * 0.5;
//   $sub = round($sub / 0.5, 0) * 0.5;

  $sql = "UPDATE boletim SET bimestre1 = '$bimestre1', bimestre2 = '$bimestre2', 
            exame = '$exame', sub = '$sub' WHERE idBoletim = '$idBoletim'";

  mysqli_query($conexao, $sql);

  echo json_encode(true);
}

echo json_encode(false);