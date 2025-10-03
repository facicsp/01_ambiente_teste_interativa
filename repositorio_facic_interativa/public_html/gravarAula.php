<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/cadastro.css">
</head>

<body>
<?php

if (isset($_SESSION["usuario"])) {
    if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
        //conteudo do site
        // include "topo.php";
        include 'conexao.php';
        
        $seguranca    = new Seguranca();
        $descricao    = $seguranca->antisql($_POST["txtDescricao"]);
        $descricao    = str_replace("'", "`", $descricao);
        $conteudo     = $_POST["elm1"];
        $conteudo     = str_replace("'", "`", $conteudo);
        $dataAula     = $seguranca->antisql($_POST["txtDataAula"]);
        // $idTurma      = $seguranca->antisql($_POST["txtTurma"]);
        $idsDisciplina = $_POST["txtDisciplina"];

        $dataAtividade = "2000-01-01";
        $bimestre = 0;

        if ($_POST["txtAvaliativo"] == 1) {  
            $dataAtividade = $seguranca->antisql($_POST["txtDataAtividade"]);
            $bimestre = $seguranca->antisql($_POST["txtBimestre"]);

            if ($bimestre != 1 && $bimestre != 2) {
                echo "<p> Ops! Houve algum erro. </p>";
                exit;
            }
        }

        if ($idsDisciplina) {
            foreach ($idsDisciplina as $dados) {
                $dados = explode("&", $dados);
                $idDisciplina = $dados[0];
                $idTurma = $dados[1];
                
                $sql = "INSERT INTO aula VALUES(null, '$descricao', '$conteudo', '$dataAula', '$dataAtividade', '$idDisciplina', '$idTurma', '$bimestre')";
                mysql_query($sql);
            }
        }

        echo "<script>alert('Gravação realizada com sucesso!'); window.location = 'cadastroAula.php';</script>";

    } else {
        echo "Acesso negado!;";
        echo "<a href='login.html'>Faça o login!</a>";
    }  
} else {
    echo "<script>alert('É necessário fazer o login!'); window.location='login.html';</script>";
}