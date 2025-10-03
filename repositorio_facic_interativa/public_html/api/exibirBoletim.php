<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

$sqlDisciplina = "SELECT disciplina.disciplina, disciplina.idDisciplina, boletim.* FROM boletim 
    INNER JOIN disciplina ON disciplina.idDisciplina = boletim.idDisciplina WHERE boletim.idaluno = '$idAluno' AND disciplina.semestre = '". SEMESTRE ."'";
    
$resultDisciplina = mysql_query($sqlDisciplina);
$linhasDisciplina = mysql_num_rows($resultDisciplina);

if ($linhasDisciplina > 0) {

  include '../funcaoNotas.php';

  $boletim = [];

  for ($i = 0; $i < $linhasDisciplina; $i++) {
    $soma = 0;
    $porcentagem = 0;
    $media = 0;
    $status = "Andamento";
    $idDisciplina = mysql_result($resultDisciplina, $i, "idDisciplina");
    $disciplina = mysql_result($resultDisciplina, $i, "disciplina");
    $bimestre1 = mysql_result($resultDisciplina, $i, "bimestre1");
    $bimestre2 = mysql_result($resultDisciplina, $i, "bimestre2");
    $exame = mysql_result($resultDisciplina, $i, "exame");
    $sub = mysql_result($resultDisciplina, $i, "sub");
    $t1 = mysql_result($resultDisciplina, $i, "t1");
    $t2 = mysql_result($resultDisciplina, $i, "t2");
    $media = ($bimestre1 + $bimestre2 + $t1 + $t2) / 2;

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

    $bim1 = ($bimestre1 + $t1) == 0 ? 0 : number_format(($bimestre1 + $t1), 1, '.', '');
    $bim2 = ($bimestre2 + $t2) == 0 ? 0 : number_format(($bimestre2 + $t2), 1, '.', '');
    
    //gambiarra
    $b1 = $bimestre1;
    $b2 = $bimestre2;
    //fimgambiarra
    
    $bimestre1 = $bim1;
    $bimestre2 = $bim2;
    
    $media = ($bimestre1 + $bimestre2) / 2;

    if ($media < 7) {
      if ($bimestre1 >= $bimestre2) {
        if ($bimestre1 < $sub) {
          $media = ($sub + $bimestre2 + $t1 + $t2) / 2;
        }
      } else {
        if ($bimestre2 < $sub) {
          $media = ($bimestre1 + $sub + $t1 + $t2) / 2;
        }
      }
    }

    if (($media >= 7 && ($exame == "" || $exame == 0 || $exame == null)) || ($media + $exame >= 10)) {
      $status = 'Aprovado';
    } else {
      $status = 'Reprovado';
    }

    if ($bimestre1 == null || $bimestre2 == null) {
      $status = 'Andamento';
      $media = '';
    }

    $boletim[] = [
      "idDisciplina" => $idDisciplina,
      "disciplina" => $disciplina,
      "bimestre1"  => $b1,
      "virtual1" => $virtual1,
      "bimestre2"  => $b2,
      "virtual2" => $virtual2,
      "sub"  => $sub,
      "exame"  => $exame,
      "bim1" => $bim1,
      "bim2" => $bim2,
      "status" => "$media $status"
    ];
  }

  echo json_encode($boletim);

} else echo json_encode([]);