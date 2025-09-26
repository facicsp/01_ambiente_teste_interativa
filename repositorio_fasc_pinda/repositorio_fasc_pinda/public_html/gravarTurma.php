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
$descricao = $_POST["txtDescricao"];
$idCurso = $_POST["txtCurso"];
$sql = "INSERT INTO turma VALUES(null,'$descricao','$idCurso','sim', '".$_SESSION['semestre']."')";
//echo $sql;
mysql_query($sql);
echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastroTurma.php';
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