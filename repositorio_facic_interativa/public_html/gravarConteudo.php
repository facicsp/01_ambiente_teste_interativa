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
      $idAula = $_SESSION["idAula"];
      $sqlConteudo = "SELECT idConteudo FROM conteudo WHERE idAula='$idAula'";
      $result = mysql_query($sqlConteudo);
      $linhas = mysql_num_rows($result);
      
      $nomeArquivo = md5(time() . $idDisciplina);
      
    //   $nomeArquivo = "";
    //   if($linhas > 0){
    //       $linhas++;
    //       $nomeArquivo = "Aula$idAula"."_$linhas";
    //   }else{
    //       $nomeArquivo = "Aula$idAula"."_1";
    //   }
      
      
      $uploadpast = 'arquivos/';

$uploadarq = $uploadpast . $_FILES['txtArquivo']['name'];
$arquivo = explode(".", $uploadarq);
$extensao = $arquivo[1];
$uploadarq = $uploadpast . $nomeArquivo . ".$extensao";
$caminho = 'arquivos\UploadEx.txt';
print pathinfo( $caminho, PATHINFO_DIRNAME );
if (move_uploaded_file($_FILES['txtArquivo']['tmp_name'], $uploadarq)){
echo " Arquivo Enviado ";}
else {echo "Arquivo não enviado - $script";}

$titulo = $seguranca->antisql($_POST["txtTitulo"]);
$sql = "INSERT INTO conteudo VALUES(null,'$titulo','$uploadarq','$idAula')";
//echo $sql;
mysql_query($sql);
echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastroConteudo.php';
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