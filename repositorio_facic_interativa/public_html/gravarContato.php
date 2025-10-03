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
      $idAluno = $seguranca->antisql($_POST["idAluno"]);
      $nome = $seguranca->antisql($_POST["nome"]);
      $email = $seguranca->antisql($_POST["email"]);
      $assunto = $seguranca->antisql($_POST["txtAssunto"]);
      $mensagem = $seguranca->antisql($_POST["txtMensagem"]);
      $idProfessor = $_SESSION["id"];
      $tipo = "professor";
      date_default_timezone_set('America/Sao_Paulo');
      $data = Date("Y-m-d");
      
      $sql = "show table status like 'contato'";
      $result = mysql_query($sql);
      $idContato = mysql_result($result, 0, "Auto_increment");
      
      
      $sql = "INSERT INTO contato VALUES('$idContato','$assunto','$idAluno','$idProfessor')";
//echo $sql;
    mysql_query($sql);
    $sql = "INSERT INTO mensagem VALUES(null,'$mensagem','$data','$tipo','nao','$idContato')";
    mysql_query($sql);
echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'dadosTurma.php';
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