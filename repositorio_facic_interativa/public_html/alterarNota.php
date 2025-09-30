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
  if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor"){
      //conteudo do site
include "topo.php";

      include 'LoginRestrito/conexao.php';
$seguranca = new Seguranca();
$idAtividade = $seguranca->antisql($_POST["idAtividade"]);
$nota = $seguranca->antisql($_POST["txtNota"]);
$nota = str_replace(",", ".", $nota);
$retorno = $seguranca->antisql($_POST["txtRetorno"]);
$sql="UPDATE atividade SET nota = '$nota',retorno='$retorno' WHERE idAtividade = '$idAtividade'";
echo $sql;
mysqli_query($conexao, $sql);
$idAula = $_SESSION["idAulaNota"];
echo "<script>
    alert('Alteração realizada com sucesso!');
    window.location='visualizarAtividades.php?id=$idAula';
</script>";

}else{
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