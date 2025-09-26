<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);
$idAtividade = $seguranca->antisql($_REQUEST["idAtividade"]);

$sql = "DELETE FROM atividade WHERE idatividade = $idAtividade AND idAluno = $idAluno";
$result = mysql_query($sql);

echo json_encode(true);