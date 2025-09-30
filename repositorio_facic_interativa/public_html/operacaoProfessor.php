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

                include 'LoginRestrito/conexao.php';
                $seguranca = new Seguranca();
                $operacao = $seguranca->antisql($_POST["operacao"]);
                $id = $seguranca->antisql($_POST["id"]);

                if ($operacao == 'alterar') {
                    $nome = $seguranca->antisql($_POST["txtNome"]);
                    $email = $seguranca->antisql($_POST["txtEmail"]);
                    $senha = $seguranca->antisql($_POST["txtSenha"]);
                    if ($senha == "") {
                        $sql = "UPDATE professor SET nome = '$nome',email='$email' WHERE idProfessor = $id";
                    } else {
                        $sql = "UPDATE professor SET nome = '$nome',email='$email',senha=md5('$senha') WHERE idProfessor = $id";
                    }
                    mysqli_query($conexao, $sql);
                    echo "<script>
    alert('Alteração realizada com sucesso!');
    window.location='cadastroProfessor.php';
</script>";
                } else if ($operacao == 'excluir') {

                    if ($_SESSION["tipo"] == "professor") {
                        echo "<script>
    alert('Você não tem permissão para esta operação!');
    window.location='cadastroProfessor.php';
</script>";
                    }else{

                    $sql = "DELETE FROM professor WHERE idprofessor = $id";
                    mysqli_query($conexao, $sql);
                    echo "<script>
    alert('Exclusão realizada com sucesso!');
    window.location='cadastroProfessor.php';
</script>";
                    }}
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