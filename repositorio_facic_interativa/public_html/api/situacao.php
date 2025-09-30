<?php

include 'hasAccess.php';
include 'util/mime_content_type.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

$sql = "SELECT DATE_FORMAT(matricula.data,'%d/%m/%Y') AS data,turma.turma,turma.idcurso,matricula.idMatricula FROM matricula,turma WHERE matricula.idAluno = '$idAluno' AND matricula.idTurma = turma.idTurma";

$result = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($result);

if ($linhas > 0) {
  $dados = [];
  
  for ($i=0; $i<$linhas; $i++) {
    $data = mysql_result($result, $i, "data");
    $turma = mysql_result($result, $i, "turma");
    $idCurso = mysql_result($result, $i, "idCurso");
    $idMatricula = mysql_result($result, $i, "idMatricula");
    
    $sqlCurso = "SELECT curso.descricao FROM curso WHERE idcurso = '$idCurso'";
    $resultadosCurso = mysqli_query($conexao, $sqlCurso);
    $linhasCurso = mysqli_num_rows($resultadosCurso);
    
    $curso = "";
    if($linhasCurso > 0) $curso = mysql_result($resultadosCurso, 0, "descricao");
    
    $dados[] = array(
      "data" => $data, 
      "turma" => $turma, 
      "curso" => $curso, 
      "idMatricula" => $idMatricula
    );
  }

  echo json_encode($dados);
} else echo json_encode([]);