<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

$sqlDisciplina= "SELECT iddisciplina,disciplina FROM disciplina WHERE idturma in(select idturma FROM matricula WHERE idaluno = '$idAluno') UNION
select l.idDisciplina,d.disciplina FROM listadisciplina l,disciplina d WHERE l.idAluno = '$idAluno' AND l.idDisciplina = d.idDisciplina";
$resultDisciplina = mysql_query($sqlDisciplina);
$linhasDisciplina = mysql_num_rows($resultDisciplina);

if ($linhasDisciplina > 0) {
  $notas = [];

  while($rowDisciplina = mysql_fetch_assoc($resultDisciplina)) {
    $disciplina = $rowDisciplina;
    $idDisciplina = $rowDisciplina["iddisciplina"];

    $sql = "SELECT aula.descricao, DATE_FORMAT(atividade.data,'%d/%m/%Y') AS dataAula, atividade.nota, atividade.retorno, atividade.idAtividade FROM atividade, aula WHERE atividade.idaluno = '$idAluno' AND atividade.iddisciplina = '$idDisciplina' AND atividade.idaula = aula.idaula";
    $result = mysql_query($sql);
    $linhas = mysql_num_rows($result);

    if ($linhas > 0) {
      $rows = [];

      while($row = mysql_fetch_assoc($result)) $rows[] = $row;

      $disciplina["notas"] = $rows;
    }
    
    $notas[] = $disciplina;
  }

  echo json_encode($notas);
} else echo json_encode([]);

exit;