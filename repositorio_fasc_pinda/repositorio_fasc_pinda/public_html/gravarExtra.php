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
  if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "aluno"){
      //atividade do site
      include "topo.php";
      include 'LoginRestrito/conexao.php';
      $seguranca = new Seguranca();
      $idAluno = $_SESSION["id"];
      $sqlAtividade = "SELECT idExtra FROM extra WHERE idAluno='$idAluno'";
      $result = mysqli_query($conexao, $sqlAtividade);
      $linhas = mysqli_num_rows($result);
      $nomeArquivo = "";
      if($linhas > 0){
          $linhas++;
          $nomeArquivo = "Extra$idAluno"."_$linhas";
      }else{
          $nomeArquivo = "Extra$idAluno"."_1";
      }
      
      
      $uploadpast = 'extras/';
if($_FILES['txtArquivo']['size'] <= 2500000){

$uploadarq = $uploadpast . $_FILES['txtArquivo']['name'];
$arquivo = explode(".", $uploadarq);
$extensao = $arquivo[1];
if($extensao == "xlsm" || $extensao == "pdf" || $extensao == "doc" || $extensao == "docx" || $extensao == "xls" || $extensao == "xlsx" || $extensao == "ppt" || $extensao == "pptx" || $extensao == "jpg" || $extensao == "txt" || $extensao == "rar" || $extensao == "zip" || $extensao == ""){
if($extensao != ""){
$uploadarq = $uploadpast . $nomeArquivo . ".$extensao";
$caminho = 'arquivos\UploadEx.txt';
print pathinfo( $caminho, PATHINFO_DIRNAME );
if (move_uploaded_file($_FILES['txtArquivo']['tmp_name'], $uploadarq)){
echo " Arquivo Enviado ";}
else {echo "Arquivo não enviado - $script";}
}else{
    $uploadarq = "";
}
$titulo = $seguranca->antisql($_POST["txtTitulo"]);
$data = $seguranca->antisql($_POST["data"]);
$obs = $seguranca->antisql($_POST["txtObservacao"]);
$idTipo = $seguranca->antisql($_POST["txtTipo"]);
$idAluno = $_SESSION["id"];
if(isset($_SESSION["disciplina"])){
$idDisciplina = $_SESSION["disciplina"];
$sql = "INSERT INTO extra VALUES(null,'$idTipo','$idDisciplina','$idAluno','$titulo','0','$obs','Enviado','$uploadarq','$data')";
//echo $sql;
mysqli_query($conexao, $sql);
echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'index.php';
</script>
";
}else{
    echo "<script>"
    . "alert('É necessário escolher uma disciplina.');"
            . "window.location='index.php';"
            . "</script>";
}
}else{
    echo "<script>
alert('Extensão inválida! O arquivo deve ter algum dos seguintes formatos: Pdf,Doc,Docx,Xls,Xlsx,Ppt,Pptx,Jpg,Txt,Rar,Zip.');
window.location = 'cadastroAtividade.php';
</script>";
}
}else{
    echo "<script>
alert('O arquivo enviado ultrapassa o limite! Reduza o tamanho.');
window.location = 'cadastroAtividade.php';
</script>";
}
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