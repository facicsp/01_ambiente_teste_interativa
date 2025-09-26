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
            if ($_SESSION["tipo"] == "administrador") {
                //conteudo do site
                include "topo.php";
                include 'conexao.php';
                $seguranca = new Seguranca();
                $idTurma = $seguranca->antisql($_POST["txtTurma"]);
                $idTurmaNova = $seguranca->antisql($_POST["txtTurmaNova"]);
                $data = $seguranca->antisql($_POST["txtData"]);

                $buscaAlunos = "select idAluno from matricula where idturma = '$idTurma'";
                $result = mysql_query($buscaAlunos);
                $linhas = mysql_num_rows($result);
                if ($linhas > 0) {

                    for ($i = 0; $i < $linhas; $i++) {
                        $idAluno = mysql_result($result, $i, "idAluno");
                        $sql = "INSERT INTO matricula VALUES(null,'$idAluno','$idTurmaNova','$data')";
                        mysql_query($sql);
                    }

                    echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastroCopiarTurma.php';
</script>
";
                } else {
                    echo "<p>Nenhum aluno encontrado.</p>";
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