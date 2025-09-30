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
$idFrequencia = $seguranca->antisql($_POST["idFrequencia"]);
$frequencia = $seguranca->antisql($_POST["txtFrequencia"]);
$sql="UPDATE pontosfrequencia SET frequencia = '$frequencia' WHERE idFrequencia = '$idFrequencia'";
//echo $sql;
mysqli_query($conexao, $sql);
$idDisciplina = $_SESSION["idDisciplina"];
echo "<script>
    window.location='registrarPontoFrequencia.php?idDisciplina=$idDisciplina';
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