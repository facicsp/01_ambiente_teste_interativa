<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idBoletim = $seguranca->antisql($_POST["idBoletim"]);
$idDisciplina = $seguranca->antisql($_POST["idDisciplina"]);
$bimestre1 = $seguranca->antisql($_POST["bimestre1"]);
$bimestre2 = $seguranca->antisql($_POST["bimestre2"]);
$exame = $seguranca->antisql($_POST["exame"]);
$sub = $seguranca->antisql($_POST["sub"]);
$t1 = $seguranca->antisql($_POST["virtual1"]);
$t2 = $seguranca->antisql($_POST["virtual2"]);

$sql = "UPDATE boletim SET bimestre1='$bimestre1',bimestre2='$bimestre2',
  exame='$exame',sub='$sub',t1='$t1',t2='$t2' WHERE idBoletim = '$idBoletim'";

mysql_query($sql);

echo json_encode(true);