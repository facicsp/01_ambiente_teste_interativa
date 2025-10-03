<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idProfessor  = $seguranca->antisql($_POST["id"]);
$descricao    = $seguranca->antisql($_POST["titulo"]);
$conteudo     = $seguranca->antisql($_POST["conteudo"]);
$dataAula     = $seguranca->antisql($_POST["data"]);
$idTurma      = $seguranca->antisql($_POST["turma"]);
$idDisciplina = $seguranca->antisql($_POST["disciplina"]);

$dataAtividade = "0000-00-00";
$bimestre = 0;

if ($_POST["avaliativo"] == 1) {  
  $dataAtividade = $seguranca->antisql($_POST["dataEntrega"]);
  $bimestre = $seguranca->antisql($_POST["bimestre"]);
}

$sql = "INSERT INTO aula VALUES(null,'$descricao','$conteudo','$dataAula','$dataAtividade','$idDisciplina','$idTurma', '$bimestre')";
mysql_query($sql);

echo json_encode(true);