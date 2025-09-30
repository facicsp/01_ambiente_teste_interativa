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
      $idAula = $seguranca->antisql($_SESSION["idAulaGeral"]);
      $idAluno = $seguranca->antisql($_SESSION["id"]);
      $sqlAtividade = "SELECT idAtividade FROM atividade WHERE idAula='$idAula'";
      $result = mysqli_query($conexao, $sqlAtividade);
      $linhas = mysqli_num_rows($result);
      $nomeArquivo = "";
      if($linhas > 0){
          $linhas++;
          $nomeArquivo = "Atividade$idAula"."-$idAluno"."_$linhas";
      }else{
          $nomeArquivo = "Atividade$idAula"."-$idAluno"."_1";
      }
      
      
      $uploadpast = 'atividades/';
      if($_FILES['txtArquivo']['size'] == 0){
          echo "<script>alert('Não Tem arquivo')</script>";
          $titulo = $seguranca->antisql($_POST["txtTitulo"]);
$data = $seguranca->antisql($_POST["data"]);
$hora = $seguranca->antisql($_POST["hora"]);
$obs = $seguranca->antisql($_POST["txtObservacao"]);
$idAluno = $_SESSION["id"];
$idDisciplina = $_SESSION["disciplina"];
$sql = "INSERT INTO atividade VALUES(null,'$titulo','','$idAula','$idAluno','$data','$hora','$obs','$idDisciplina','0','','')";
//echo $sql;
if(mysqli_query($conexao, $sql)){

    $result = mysqli_query($conexao, "SELECT idAtividade FROM atividade WHERE idAula = '$idAula' AND idAluno = '$idAluno' 
        AND idDisciplina = '$idDisciplina' AND hora = '$hora' ORDER BY idAtividade DESC LIMIT 1");
    
    if (mysqli_num_rows($result) == 1) {

        $idAtividade = mysql_result($result, 0, "idAtividade");
        $_SESSION["idAtividade"] = $idAtividade;
        $_SESSION["atividadeArquivo"] = "Sem arquivo";

        echo "<script>
        alert('Gravação realizada com sucesso!');
        window.location = 'mail/envioAtividade.php';
        </script>";

    } else {
        echo "<script>
        alert('Ops! Houve algum erro.');
        window.location = 'cadastroAtividade.php';
        </script>";
    }

}else{
    echo mysqli_error($conexao);
}

      }else{
          
      
if($_FILES['txtArquivo']['size'] <= 31500000){

$uploadarq = $uploadpast . $_FILES['txtArquivo']['name'];
$arquivo = explode(".", $uploadarq);
$extensao = $arquivo[1];
if($extensao == "pdf" || $extensao == "doc" || $extensao == "docx" || $extensao == "xls" || $extensao == "xlsx" || $extensao == "ppt" || $extensao == "pptx" || $extensao == "jpg" || $extensao == "txt" || $extensao == "rar" || $extensao == "zip"){
$uploadarq = $uploadpast . $nomeArquivo . ".$extensao";
$caminho = 'arquivos\UploadEx.txt';
print pathinfo( $caminho, PATHINFO_DIRNAME );
if (move_uploaded_file($_FILES['txtArquivo']['tmp_name'], $uploadarq)){
echo " Arquivo Enviado ";}
else {echo "Arquivo não enviado - $script";}

$titulo = $seguranca->antisql($_POST["txtTitulo"]);
$data = $seguranca->antisql($_POST["data"]);
$hora = $seguranca->antisql($_POST["hora"]);
$obs = $seguranca->antisql($_POST["txtObservacao"]);
$idAluno = $_SESSION["id"];
$idDisciplina = $_SESSION["disciplina"];
$sql = "INSERT INTO atividade VALUES(null,'$titulo','$uploadarq','$idAula','$idAluno','$data','$hora','$obs','$idDisciplina','0','','')";
//echo $sql;
if(mysqli_query($conexao, $sql)){
    $result = mysqli_query($conexao, "SELECT idAtividade FROM atividade WHERE idAula = '$idAula' AND idAluno = '$idAluno' 
    AND idDisciplina = '$idDisciplina' AND hora = '$hora' ORDER BY idAtividade DESC LIMIT 1");

if (mysqli_num_rows($result) == 1) {

    $idAtividade = mysql_result($result, 0, "idAtividade");
    $_SESSION["idAtividade"] = $idAtividade;
    $_SESSION["atividadeArquivo"] = "$nomeArquivo.$extensao";

    echo "<script>
    alert('Gravação realizada com sucesso!');
    window.location = 'mail/envioAtividade.php';
    </script>";

} else {
    echo "<script>
    alert('Ops! Houve algum erro.');
    window.location = 'cadastroAtividade.php';
    </script>";
}
}else{
    echo mysqli_error($conexao);
}

}else{
    echo "<script>
alert('Extensão inválida! O arquivo deve ter algum dos seguintes formatos: Pdf,Doc,Docx,Xls,Xlsx,Ppt,Pptx,Jpg,Txt,Rar,Zip.');
window.location = 'cadastroAtividade.php';
</script>";
}
}else{
    echo "<script>
alert('O arquivo enviado ultrapassa o tamanho máximo permitido(30MB)! Reduza o tamanho e tente novamente.');
window.location = 'cadastroAtividade.php';
</script>";
}
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