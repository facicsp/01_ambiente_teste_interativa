<?php
  
session_start();
include 'conexao.php';
  
  if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
          $seguranca = new Seguranca();
          $id = $seguranca->antisql($_GET["id"]);
          
          $result = mysql_query("SELECT * FROM aplicarprova WHERE idProva = '$id'");
          if (mysql_num_rows($result) == 0) {
            $idProfessor = $_SESSION["id"];
            mysql_query("DELETE FROM prova WHERE idProva = '$id' AND idProfessor = '$idProfessor'");
            exit("<script>alert('Exclusão realizada com sucesso!'); window.location = 'visualizarProvas2.php'; </script>");
          }
        }
  
  }
  
    echo "<script>alert('Ops! Esse questionário já foi aplicado e não é possível remove-lo.'); window.location = 'visualizarProvas2.php'; </script>";
 
      