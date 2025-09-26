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
  if($_SESSION["tipo"] == "aluno" || $_SESSION["tipo"] == "professor"){
      //conteudo do site
      include 'conexao.php';
      $seguranca = new Seguranca();
      $idUsuario = $_SESSION["id"];
      $sub = $seguranca->antisql($_POST["txtSubResposta"]);
      $tipo = $_SESSION["tipo"];
      $idComentario = $seguranca->antisql($_POST["idComentario"]);
      $idTopico = $seguranca->antisql($_POST["topico"]);
      $sql = "INSERT INTO subresposta VALUES(null,'$sub','$idUsuario','$tipo','$idComentario')";
    
    //exit($sql);
    
    mysql_query($sql);
echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'forum.php?idTopico=$idTopico';
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