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
$operacao = $seguranca->antisql($_POST["operacao"]);
$id = $seguranca->antisql($_POST["id"]);

if($operacao == 'alterar'){
$idAluno = $seguranca->antisql($_POST["txtAluno"]);
$idTurma = $seguranca->antisql($_POST["txtTurma"]);
$data = $seguranca->antisql($_POST["txtData"]);

$sql="UPDATE matricula SET idTurma = '$idTurma', idAluno = '$idAluno',data='$data' WHERE idMatricula = $id";
mysqli_query($conexao, $sql);
echo "<script>
    alert('Alteração realizada com sucesso!');
    window.location='cadastroMatricula.php';
</script>";
}else if($operacao == 'excluir'){
$idAluno = $seguranca->antisql($_POST["idAluno"]);
$idTurma = $seguranca->antisql($_POST["idTurma"]);
$sql = "DELETE FROM matricula WHERE idMatricula = $id";
mysqli_query($conexao, $sql);
$sql = "select idDisciplina from disciplina where idTurma = $idTurma";
//echo $sql;
$result = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($result);
if($linhas > 0){
    for($i=0;$i<$linhas;$i++){
        $idDisciplina = mysql_result($result, $i, "idDisciplina");
        $sql2 = "DELETE FROM boletim WHERE idDisciplina = '$idDisciplina' and idAluno = '$idAluno'";
        //echo $sql2;
        mysqli_query($conexao, $sql2);
    }
}

echo "<script>
    alert('Exclusão realizada com sucesso!');
    window.location='cadastroMatricula.php';
</script>";
}
else if($operacao == "excluirdisciplina"){
    $idAluno = $seguranca->antisql($_POST["idAluno"]);
    
$sql = "select idDisciplina from listadisciplina where idListaDisciplina = $id";
//echo $sql;
$result = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($result);
if($linhas > 0){
    for($i=0;$i<$linhas;$i++){
        $idDisciplina = mysql_result($result, $i, "idDisciplina");
        $sql2 = "DELETE FROM boletim WHERE idDisciplina = '$idDisciplina' and idAluno = '$idAluno'";
        //echo $sql2;
        mysqli_query($conexao, $sql2);
    }
}
$sql = "DELETE FROM listadisciplina WHERE idListaDisciplina = $id";
mysqli_query($conexao, $sql);


echo "<script>
    alert('Exclusão realizada com sucesso!');
    window.location='cadastroMatriculaAdaptado.php';
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