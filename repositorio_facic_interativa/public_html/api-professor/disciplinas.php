<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idProfessor = $seguranca->antisql($_REQUEST["id"]);

$sql = "SELECT idDisciplina, disciplina, disciplina.idTurma, turma.turma FROM disciplina, turma 
        WHERE idProfessor = '$idProfessor' AND disciplina.idTurma = turma.idTurma";
$result = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($result);

if ($linhas > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  echo json_encode($rows);

} else echo json_encode([]);