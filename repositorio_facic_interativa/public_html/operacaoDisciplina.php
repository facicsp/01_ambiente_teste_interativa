<?php
  session_start();
  ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/cadastro.css">
  </head>
  
  <body>
    <?php
  if (isset($_SESSION["usuario"])) {
    if ($_SESSION["tipo"] == "administrador") {
      //conteudo do site
      include "topo.php";
      
      include 'conexao.php';
      $seguranca = new Seguranca();
      $operacao = $seguranca->antisql($_POST["operacao"]);
      $id = $seguranca->antisql($_POST["id"]);
      
      if ($operacao == 'alterar') {
        $idProfessor = $seguranca->antisql($_POST["txtProfessor"]);
        $idProfessorAux = $seguranca->antisql($_POST["txtProfessorAuxiliar"]);
        $idTurma = $seguranca->antisql($_POST["txtTurma"]);
        $disciplina = $seguranca->antisql($_POST["txtDisciplina"]);
        $cargaHoraria = $seguranca->antisql($_POST["txtCargaHoraria"]);
        $inicio = $seguranca->antisql($_POST["txtInicio"]);
        $termino = $seguranca->antisql($_POST["txtTermino"]);
        $credito = $seguranca->antisql($_POST["txtCredito"]);
        $sql = "UPDATE disciplina SET idProfessor = '$idProfessor',disciplina='$disciplina',cargaHoraria='$cargaHoraria', credito='$credito',idTurma='$idTurma',inicio='$inicio',termino='$termino' WHERE idDisciplina = $id";
        
        mysql_query($sql);
        mysql_query("DELETE FROM relacao_professor_auxiliar WHERE idDisciplina = '$id'");
        
        if ($idProfessorAux > 0) {
          mysql_query("INSERT INTO relacao_professor_auxiliar VALUES (NULL, '$idProfessorAux', '$id')");
        }
        
        
        echo "<script>
          alert('Alteração realizada com sucesso!');
          window.location='cadastroDisciplina.php';
          </script>";
      } else if ($operacao == 'excluir') {
        
        mysql_query("DELETE FROM video  WHERE iddisciplina = $id");
        mysql_query("DELETE FROM disciplina WHERE idDisciplina = $id");
        
        echo "<script>
          alert('Exclusão realizada com sucesso!');
          window.location='cadastroDisciplina.php';
          </script>";
      }
    } else {
      echo "Acesso negado!;";
      echo "<a href='login.html'>Faça o login!</a>";
    }
  } else {
    echo "<script>"
      . "alert('É necessário fazer o login!');"
      . "window.location='login.html';"
      . "</script>";
  }
  ?>