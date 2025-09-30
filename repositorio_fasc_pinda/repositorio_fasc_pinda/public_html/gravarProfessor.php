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
      include 'LoginRestrito/conexao.php';
      $seguranca = new Seguranca();
$nome = $seguranca->antisql($_POST["txtNome"]);
$email = $seguranca->antisql($_POST["txtEmail"]);
$senha = $seguranca->antisql($_POST["txtSenha"]);
$sql = "INSERT INTO professor VALUES(null,'$nome','$email',md5('$senha'))";
//echo $sql;
mysqli_query($conexao, $sql);
echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastroProfessor.php';
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