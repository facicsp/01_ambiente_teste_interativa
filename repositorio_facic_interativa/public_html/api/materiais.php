<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

$sql = "SELECT idDisciplina,disciplina FROM disciplina 
        WHERE idTurma IN(SELECT matricula.idTurma FROM matricula, turma WHERE idAluno = '$idAluno' 
        AND matricula.idTurma = turma.idTurma 
        AND turma.ativo ='sim') UNION 
        SELECT l.idDisciplina,d.disciplina FROM listadisciplina l, disciplina d 
        WHERE l.idAluno = '$idAluno' 
        AND l.idDisciplina = d.idDisciplina";

$resultados = mysql_query($sql);
$linhas = mysql_num_rows($resultados);

if ($linhas > 0) {
  $data = [];

  for ($i = 0; $i < $linhas; $i++) {
    $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
    $disciplina = mysql_result($resultados, $i, "disciplina");

    $data[$i] = ["idDisciplina" => $idDisciplina, "disciplina" => $disciplina];

    $sqlArquivo = "SELECT c.idConteudo, c.titulo, c.arquivo FROM conteudo c,aula a 
      WHERE a.idDisciplina = '$idDisciplina' 
      AND c.idAula = a.idAula";

    $resultArquivo = mysql_query($sqlArquivo);
    $linhasArquivo = mysql_num_rows($resultArquivo);

    if($linhasArquivo > 0) {
      $arquivos = [];

      while($arquivo = mysql_fetch_assoc($resultArquivo)) $arquivos[] = $arquivo;

      $data[$i]["arquivos"] = $arquivos;
    }
  }

  echo json_encode($data);
  exit;
} 

echo json_encode([]);