<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
    </head>
    <body>
        <?php
        session_start();
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "aluno") {
                //conteudo do site


                include 'LoginRestrito/conexao.php';

                $seguranca = new Seguranca();
                $quantidade = $seguranca->antisql($_POST["quantidade"]);
                for($i=0;$i < $quantidade;$i++){
                $idPergunta = $seguranca->antisql($_POST["idpergunta$i"]);
                $idResposta = $seguranca->antisql($_POST["resposta$i"]);
                $idAluno = $_SESSION["id"];
                $idDisciplina = $_SESSION["disciplina"];
                $sql = "INSERT INTO questionario VALUES(null,'$idAluno','$idDisciplina','$idPergunta','$idResposta')";
                echo $sql;
                mysqli_query($conexao, $sql);
                
                
                }
                /*
                $sqlResposta = "SELECT correta FROM resposta WHERE idResposta = '$idResposta'";
                $resultResposta = mysqli_query($conexao, $sqlResposta);
                $correta = mysql_result($resultResposta, 0, "correta");
                if($correta == "sim"){
                    $mensagem = "RESPOSTA CORRETA!";
                }else if($correta == "nao"){
                    $mensagem = "RESPOSTA ERRADA!";
                }
                echo "<script>"
                . "alert('$mensagem');"
                        . "</script>";
                
                $idAluno = $_SESSION["id"];
                */
                echo "<script>
//alert('Gravação realizada com sucesso!');
window.location = 'resultado.php';
</script>";
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