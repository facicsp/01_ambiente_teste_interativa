<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);
$idAtividade = $seguranca->antisql($_REQUEST["idAtividade"]);

$sql = "DELETE FROM atividade WHERE idatividade = $idAtividade AND idAluno = $idAluno";
$result = mysqli_query($conexao, $sql);

echo json_encode(true);