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
  if($_SESSION["tipo"] == "administrador"){
      //conteudo do site
      include "topo.php";
      include 'conexao.php';
$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_POST["txtAluno"]);
$idTurma = $seguranca->antisql($_POST["txtTurma"]);
$data = $seguranca->antisql($_POST["txtData"]);
$sql = "INSERT INTO matricula VALUES(null,'$idAluno','$idTurma','$data')";
//echo $sql;
mysql_query($sql);

echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastroMatricula.php';
</script>
";
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