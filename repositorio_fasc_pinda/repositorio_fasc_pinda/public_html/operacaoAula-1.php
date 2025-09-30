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

      include 'LoginRestrito/conexao.php';
$seguranca = new Seguranca();
$operacao = $seguranca->antisql($_POST["operacao"]);
$id = $seguranca->antisql($_POST["id"]);

if($operacao == 'alterar'){
$descricao = $seguranca->antisql($_POST["txtDescricao"]);
$descricao = str_replace("'", "`", $descricao);
$conteudo = $_POST["elm1"];
$conteudo = str_replace("'", "`", $conteudo);
$dataAula = $seguranca->antisql($_POST["txtDataAula"]);
if(isset($_POST["txtDataAtividade"])){
    $dataAtividade = $seguranca->antisql($_POST["txtDataAtividade"]);
}else{
    $dataAtividade = "2000-01-01";
}

$idTurma = $seguranca->antisql($_POST["txtTurma"]);
$idDisciplina = $seguranca->antisql($_POST["txtDisciplina"]);
$idDesafio = $seguranca->antisql($_POST["txtDesafio"]);

$sqlBimestre = "";  
  
if(isset($_POST["txtCancelarAvaliativo"])){
    $dataAtividade = "2000-01-01";
} else if (isset($_POST["txtBimestre"])) {
  if ($_POST["txtBimestre"] == 1 || $_POST["txtBimestre"] == 2) {
    $sqlBimestre = ", bimestre = '".$_POST["txtBimestre"]."'";
  }
}
  
$sql="UPDATE aula SET descricao = '$descricao',conteudo='$conteudo',dataAula='$dataAula',dataAtividade='$dataAtividade',idTurma='$idTurma',idDisciplina='$idDisciplina' $sqlBimestre WHERE idAula = $id";

if(mysqli_query($conexao, $sql)){
echo "<script>
    alert('Alteração realizada com sucesso!');
    window.location='cadastroAula.php';
</script>";
}else{
    echo "Ocorreu uma falha no sistema. Entre em contato com o desenvolvedor.";
}
}else if($operacao == 'excluir'){
$sql = "DELETE FROM aula WHERE idAula = $id";
mysqli_query($conexao, $sql);
echo "<script>
    alert('Exclusão realizada com sucesso!');
    window.location='cadastroAula.php';
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