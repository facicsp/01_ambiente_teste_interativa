<?php
$idDisciplina = $_REQUEST["idDisciplina"];
$idAluno = $_REQUEST["idAluno"];


include './conexao.php';
$sql = "select * from listadisciplina WHERE idDisciplina = '$idDisciplina' AND idAluno = '$idAluno'";
$resultados = mysql_query($sql);
$linhas = mysql_num_rows($resultados);

if($linhas > 0){
    $retorno = "erro";
    
}else{

    $sql = "INSERT INTO listadisciplina VALUES(null,'$idDisciplina','$idAluno','sim')";
    mysql_query($sql);
    $retorno = "ok";
    
echo $retorno;

    
}
?>