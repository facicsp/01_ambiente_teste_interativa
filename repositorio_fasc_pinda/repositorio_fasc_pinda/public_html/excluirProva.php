<?php
  
session_start();
include 'LoginRestrito/conexao.php';
  
  if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
          $seguranca = new Seguranca();
          $id = $seguranca->antisql($_GET["id"]);
          
          $result = mysqli_query($conexao, "SELECT * FROM aplicarprova WHERE idProva = '$id'");
          if (mysqli_num_rows($result) == 0) {
            $idProfessor = $_SESSION["id"];
            mysqli_query($conexao, "DELETE FROM prova WHERE idProva = '$id' AND idProfessor = '$idProfessor'");
            exit("<script>alert('Exclusão realizada com sucesso!'); window.location = 'visualizarProvas2.php'; </script>");
          }
        }
  
  }
  
    echo "<script>alert('Ops! Esse questionário já foi aplicado e não é possível remove-lo.'); window.location = 'visualizarProvas2.php'; </script>";
 
      