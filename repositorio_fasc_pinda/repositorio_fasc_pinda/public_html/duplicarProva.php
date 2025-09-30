<?php

session_start(); 
include 'LoginRestrito/conexao.php';

if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "professor") {
    $seguranca = new Seguranca();

    $_idProva = $seguranca->antisql($_GET['id']);

    $resultProva = mysqli_query($conexao, "SELECT * FROM prova WHERE idProva = '$_idProva'");
    if (mysqli_num_rows($resultProva) == 0) exit("<script>window.location = 'visualizarProvas2.php';</script>");

    $titulo      = mysql_result($resultProva, 0, 'titulo') . " cópia";
    $idProfessor = mysql_result($resultProva, 0, 'idProfessor');

    mysqli_query($conexao, "INSERT INTO prova VALUES (NULL, '$titulo', '$idProfessor');");
    $resultInsert = mysqli_query($conexao, "SELECT idProva FROM prova WHERE idProfessor = '$idProfessor' ORDER BY idProva DESC LIMIT 1");
    $idProva = mysql_result($resultInsert, 0, 'idProva');

    $result = mysqli_query($conexao, "SELECT * FROM questao2 WHERE idProva = '$_idProva'");

    if (mysqli_num_rows($result) == 0) exit("<script>window.location = 'visualizarProvas2.php';</script>");

    for ($i=0; $i<mysqli_num_rows($result); $i++) {
        $descricao = $seguranca->antisql(mysql_result($result, $i, 'descricao'));
        $idQuestao = mysql_result($result, $i, 'idQuestao');
        $tipo = mysql_result($result, $i, 'tipo');
        $peso = mysql_result($result, $i, 'peso');
        
        mysqli_query($conexao, "INSERT INTO questao2 VALUES (NULL, '$descricao', '$tipo', '$peso', '$idProva');");
        $resultInsert = mysqli_query($conexao, "SELECT idQuestao FROM questao2 WHERE idProva = '$idProva' ORDER BY idQuestao DESC LIMIT 1");
        $_idQuestao = mysql_result($resultInsert, 0, 'idQuestao');

        if ($tipo == 'objetiva') {
            $resultAlt = mysqli_query($conexao, "SELECT * FROM alternativa WHERE idQuestao = '$idQuestao'");
            for ($ialt=0; $ialt < mysqli_num_rows($resultAlt); $ialt++) {
                $alternativa = $seguranca->antisql(mysql_result($resultAlt, $ialt, 'alternativa'));
                $correta = mysql_result($resultAlt, $ialt, 'correta');

                mysqli_query($conexao, "INSERT INTO alternativa VALUES (NULL, '$alternativa', '$correta', '$_idQuestao');");
            }
        }
    }

}

exit("<script>window.location = 'visualizarProvas2.php';</script>");

?>