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
            if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
                //conteudo do site
                include "topo.php";
                include 'conexao.php';
                $seguranca = new Seguranca();
                $assunto = $seguranca->antisql($_POST["txtAssunto"]);
                $mensagem = $seguranca->antisql($_POST["txtMensagem"]);
                $idTurma = $seguranca->antisql($_POST["idTurma"]);
                $idProfessor = $_SESSION["id"];
                date_default_timezone_set('America/Sao_Paulo');
                $data = Date("Y-m-d");


                if ($idTurma > 0) {
                    $sql = "INSERT INTO mensagemturma VALUES(null,'$assunto','$mensagem','$data','$idTurma','$idProfessor')";
                    //echo $sql;
                    mysql_query($sql);
                    echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'dadosTurma.php';
</script>
";
                } else {
                    echo "<script>
alert('Antes de enviar a mensagem, é necessário escolher uma turma válida!');
window.location = 'dadosTurma.php';
</script>
";
                }
            } else {
                echo "Acesso negado!;";
                echo "<a href='login.html'>Faça o login!</a>";
            }
        } else {
            echo "<script>"
            . "alert('É necessário fazer o login!');"
            . "window.location='login.html';"
            . "</script>";
        }
        ?>