<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

$listaDisciplinas = [];

$sql = "select disciplina.*,professor.nome from disciplina,professor 
where idturma in(select idturma from matricula where idaluno = '$idAluno') 
AND disciplina.idProfessor = professor.idProfessor AND semestre = '".SEMESTRE."'";
$result = mysql_query($sql);
$linhas = mysql_num_rows($result);
if ($linhas > 0) {
    for ($i = 0; $i < $linhas; $i++) {
        $idDisciplina = mysql_result($result, $i, "idDisciplina");
        $listaDisciplinas[] = $idDisciplina;
    }
}
$sql = "select disciplina.disciplina,usuario.nome,listadisciplina.idListaDisciplina,listadisciplina.ativo,professor.nome as professor,listadisciplina.idDisciplina
from listadisciplina,usuario,disciplina,professor
WHERE listadisciplina.idAluno = '$idAluno'
AND listadisciplina.idDisciplina = disciplina.idDisciplina
AND listadisciplina.idAluno = usuario.idUsuario
AND disciplina.idProfessor = professor.idProfessor AND semestre = '".SEMESTRE."'";
$result = mysql_query($sql);
$linhas = mysql_num_rows($result);
if ($linhas > 0) {
    for ($i = 0; $i < $linhas; $i++) {
        $idDisciplina = mysql_result($result, $i, "idDisciplina");
        $listaDisciplinas[] = $idDisciplina;
    }
}
  
$listaDisciplinas = join(",", $listaDisciplinas);

$sql = "SELECT idVideo, titulo, video FROM video WHERE idDisciplina IN($listaDisciplinas)ORDER BY idvideo DESC";

$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if($linhas > 0) {
  while($row = mysql_fetch_assoc($result)) {
    // $video = split("v=", $row["video"])[1];
    // $row["video"] = "https://youtu.be/$video";
    $row["video"] = array_reverse(split("/", $row["video"]))[0];
    $rows[] = $row;
  }

  echo json_encode($rows);
} else {
  exit(json_encode([]));
}