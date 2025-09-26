<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

$sql = "SELECT p.idProfessor, p.nome FROM disciplina,professor p WHERE idTurma 
      IN (SELECT idTurma FROM matricula WHERE idAluno = '$idAluno') 
      AND disciplina.idProfessor = p.idProfessor
      GROUP BY nome";

$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if($linhas > 0) {
  while($row = mysql_fetch_assoc($result)) {
    $rows[] = $row;
  }
} 

$sql = "SELECT professor.idProfessor, professor.nome FROM listadisciplina,usuario,disciplina,professor
WHERE listadisciplina.idAluno = '$idAluno'
AND listadisciplina.idDisciplina = disciplina.idDisciplina
AND listadisciplina.idAluno = usuario.idUsuario
AND disciplina.idProfessor = professor.idProfessor AND semestre = '".SEMESTRE."'";

$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if($linhas > 0) {
  while($row = mysql_fetch_assoc($result)) {
    $rows[] = $row;
  }
} 

echo json_encode($rows);

// else echo json_encode([]);