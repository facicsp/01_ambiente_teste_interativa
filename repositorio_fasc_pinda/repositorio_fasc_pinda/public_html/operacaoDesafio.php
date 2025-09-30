<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
    </head>
<body>
<?php
session_start();
if (isset($_SESSION["usuario"])) {
  if($_SESSION["tipo"] == "administrador"){
      //conteudo do site
include "topo.php";

include 'LoginRestrito/conexao.php';
$seguranca = new Seguranca();
$operacao = $seguranca->antisql($_POST["operacao"]);
$id = $seguranca->antisql($_POST["id"]);

if($operacao == 'alterar'){
$tipo = $seguranca->antisql($_POST["txtTipo"]);
$pontos = $seguranca->antisql($_POST["txtPontos"]);

$idDisciplina = $seguranca->antisql($_POST["txtDisciplina"]);
$sql="UPDATE desafio SET tipo = '$tipo',pontos='$pontos',idDisciplina='$idDisciplina' WHERE idDesafio = $id";
mysqli_query($conexao, $sql);
echo "<script>
    alert('Alteração realizada com sucesso!');
    window.location='cadastroDesafio.php';
</script>";
}else if($operacao == 'excluir'){
$sql = "DELETE FROM desafio WHERE idDesafio = $id";
mysqli_query($conexao, $sql);
echo "<script>
    alert('Exclusão realizada com sucesso!');
    window.location='cadastroDesafio.php';
</script>";
}
}
else{
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