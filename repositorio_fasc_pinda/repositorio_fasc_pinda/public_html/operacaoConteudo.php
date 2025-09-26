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

include 'conexao.php';
$seguranca = new Seguranca();
$operacao = $seguranca->antisql($_POST["operacao"]);
$id = $seguranca->antisql($_POST["id"]);

if($operacao == 'excluir'){
$sql = "DELETE FROM conteudo WHERE idconteudo = $id";
mysql_query($sql);
echo "<script>
    alert('Exclusão realizada com sucesso!');
    window.location='cadastroConteudo.php';
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