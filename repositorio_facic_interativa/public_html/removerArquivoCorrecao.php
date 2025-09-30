<?php

session_start();

// ALTER TABLE `atividade` ADD `arquivo_correcao` VARCHAR(500) NOT NULL AFTER `retorno`;

if (isset($_SESSION["usuario"])) {
    $idAtividade = $_GET["idAtividade"];
    $idAluno = $_GET["idAluno"];

    include 'LoginRestrito/conexao.php';

    mysqli_query($conexao, "UPDATE atividade SET arquivo_correcao='' WHERE idAtividade = '$idAtividade' AND idAluno = '$idAluno'");
}


$idDisciplina = $_GET["idDisciplina"];
$id = $_GET["id"];

echo "<script>location.href='visualizarAtividades.php?id=$id&idDisciplina=$idDisciplina';</script>";