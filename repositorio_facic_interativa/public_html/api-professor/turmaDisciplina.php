<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idProfessor = $seguranca->antisql($_REQUEST["id"]);

$sql = "SELECT turma.idturma,turma.turma from turma where idturma 
  IN (select disciplina.idTurma from disciplina where idprofessor = '$idProfessor')";
$resultados = mysql_query($sql);
$linhas = mysql_num_rows($resultados);

$dados = array(
  "turmas" => [],
  "disciplinas" => [],
  "listaDisciplinas" => []
);

if ($linhas > 0) {
  while($row = mysql_fetch_assoc($resultados)) {
    $dados["turmas"][] = $row;
  }
}

$sql = "SELECT d.*,t.turma FROM disciplina d,turma t where d.idTurma = t.idTurma and d.idProfessor = '$idProfessor' ORDER BY d.disciplina";
$resultados = mysql_query($sql);
$linhas = mysql_num_rows($resultados);

if ($linhas > 0) {
  while($row = mysql_fetch_assoc($resultados)) {
    $dados["disciplinas"][] = $row;
    $dados["listaDisciplinas"][] = $row["idDisciplina"];
  }
}

$dados["listaDisciplinas"] = implode(",", $dados["listaDisciplinas"]);

echo json_encode($dados);