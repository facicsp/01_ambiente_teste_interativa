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
$operacao = $_POST["operacao"];
$id = $_POST["id"];

if($operacao == 'alterar'){
$descricao = $_POST["txtDescricao"];
$idCurso = $_POST["txtCurso"];
$semestre = $_POST["txtSemestre"];

$sql="UPDATE turma SET turma = '$descricao', idcurso = '$idCurso', semestre = '$semestre' WHERE idturma = $id";
mysqli_query($conexao, $sql);
echo "<script>
    alert('Alteração realizada com sucesso!');
    window.location='cadastroTurma.php';
</script>";
}else if($operacao == 'excluir'){
$sql = "DELETE FROM turma WHERE idturma = $id";
mysqli_query($conexao, $sql);
echo "<script>
    alert('Exclusão realizada com sucesso!');
    window.location='cadastroTurma.php';
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