<?php

include 'hasAccess.php';
include '../funcaoNotas.php';

$seguranca = new Seguranca();
$idDisciplina = $seguranca->antisql($_REQUEST["idDisciplina"]);
$idTurma = $seguranca->antisql($_REQUEST["idTurma"]);

$sqlBoletim = "SELECT idAluno from matricula where idTurma = '$idTurma' 
  AND idAluno not in (select idAluno from boletim where idDisciplina = '$idDisciplina')";
$resultBoletim = mysqli_query($conexao, $sqlBoletim);
$linhasBoletim = mysqli_num_rows($resultBoletim);

if($linhasBoletim > 0) {
  for($n=0; $n<$linhasBoletim; $n++) {
    $idAluno = mysql_result($resultBoletim, $n, "idAluno");
    $sql = "INSERT INTO boletim VALUES(null,'$idAluno','$idDisciplina','0','0','0','0','0','0')";
    mysqli_query($conexao, $sql);
  }
}

$sqlAdaptado = "SELECT idAluno from listadisciplina where idDisciplina = '$idDisciplina' 
  AND idAluno not in (select idAluno from boletim where idDisciplina = $idDisciplina)";
$resultAdaptado = mysqli_query($conexao, $sqlAdaptado);
$linhasAdaptado = mysqli_num_rows($resultAdaptado);

if($linhasAdaptado > 0) {
  for($n=0; $n<$linhasAdaptado; $n++) {
    $idAluno = mysql_result($resultAdaptado, $n, "idAluno");
    $sql = "INSERT INTO boletim VALUES(null,'$idAluno','$idDisciplina','0','0','0','0','0','0')";
    mysqli_query($conexao, $sql);
  }
}

$sqlBoletim = "SELECT boletim.*,usuario.nome FROM boletim,usuario WHERE idDisciplina = '$idDisciplina' 
  AND boletim.idAluno = usuario.idUsuario ORDER BY usuario.nome";
$resultBoletim = mysqli_query($conexao, $sqlBoletim);
$linhasBoletim = mysqli_num_rows($resultBoletim);

if ($linhasBoletim > 0) {
  $rows = [];

  for ($i=0; $i<$linhasBoletim; $i++) {
    $idBoletim = mysql_result($resultBoletim, $i, "idBoletim");
    $idAluno = mysql_result($resultBoletim, $i, "idAluno");
    $nome = mysql_result($resultBoletim, $i, "nome");
    $idDisciplina = mysql_result($resultBoletim, $i, "idDisciplina");
    $bimestre1 = mysql_result($resultBoletim, $i, "bimestre1");
    $bimestre2 = mysql_result($resultBoletim, $i, "bimestre2");
    $exame = mysql_result($resultBoletim, $i, "exame");
    $sub = mysql_result($resultBoletim, $i, "sub");
    $t1 = mysql_result($resultBoletim, $i, "t1");
    $t2 = mysql_result($resultBoletim, $i, "t2");

    $forumBm1 = getForum($idAluno, $idDisciplina, 1);
    $forumBm2 = getForum($idAluno, $idDisciplina, 2);

    $questionario1 = getQuestionario($idAluno, $idDisciplina, 1);
    $questionario2 = getQuestionario($idAluno, $idDisciplina, 2);

    $moduloBm1 = getModulo($idAluno, $idDisciplina, 1);
    $moduloBm2 = getModulo($idAluno, $idDisciplina, 2);

    $t1 = ($forumBm1 + $questionario1 + $moduloBm1);
    $t2 = ($forumBm2 + $questionario2 + $moduloBm2);

    $virtual1 = $t1 == 0 ? 0 : number_format($t1, 1, '.', '');
    $virtual2 = $t2 == 0 ? 0 : number_format($t2, 1, '.', '');

    $rows[] = array(
      "idBoletim" => $idBoletim,
      "idAluno"   => $idAluno,
      "nome"      => $nome,
      "idDisciplina" => $idDisciplina,
      "bimestre1" => $bimestre1,
      "virtual1"  => $virtual1,
      "bimestre2" => $bimestre2,
      "virtual2"  => $virtual2,
      "sub"       => $sub,
      "exame"     => $exame
    );
  }

  echo json_encode($rows);
} else {
  $sqlAlunos = "SELECT u.idUsuario,u.nome FROM matricula m,usuario u WHERE m.idTurma = '$idTurma' 
    AND m.idAluno = u.idUsuario ORDER BY u.nome";
  $resultadosAlunos = mysqli_query($conexao, $sqlAlunos);
  $linhasAlunos = mysqli_num_rows($resultadosAlunos);

  if ($linhasAlunos > 0) {
    for ($i = 0; $i < $linhasAlunos; $i++) {
      $idAluno = mysql_result($resultadosAlunos, $i, "idUsuario");
      $sql = "INSERT INTO boletim VALUES(null,'$idAluno','$idDisciplina','0','0','0','0','0','0')";
      mysqli_query($conexao, $sql);
    }

    echo json_encode('novamente');
  }
}