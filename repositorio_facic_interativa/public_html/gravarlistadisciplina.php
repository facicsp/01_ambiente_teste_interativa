<?php
$idDisciplina = $_REQUEST["idDisciplina"];
$idAluno = $_REQUEST["idAluno"];


include 'LoginRestrito/conexao.php';
$sql = "select * from listadisciplina WHERE idDisciplina = '$idDisciplina' AND idAluno = '$idAluno'";
$resultados = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($resultados);

if($linhas > 0){
    $retorno = "erro";
    
}else{

    $sql = "INSERT INTO listadisciplina VALUES(null,'$idDisciplina','$idAluno','sim')";
    mysqli_query($conexao, $sql);
    $retorno = "ok";
    
echo $retorno;

    
}
?>