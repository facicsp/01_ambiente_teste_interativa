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
  if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "aluno"){
      //conteudo do site
     include "topo.php";
      include 'LoginRestrito/conexao.php';
      $seguranca = new Seguranca();
      $mensagem = $seguranca->antisql($_POST["txtMensagem"]);
      $idContato = $seguranca->antisql($_POST["idContato"]);
      $tipo = $_SESSION["tipo"];
      date_default_timezone_set('America/Sao_Paulo');
      $data = Date("Y-m-d");
      
    mysqli_query($conexao, $sql);
    $sql = "INSERT INTO mensagem VALUES(null,'$mensagem','$data','$tipo','nao','$idContato')";
    mysqli_query($conexao, $sql);
echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'mensagens.php';
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