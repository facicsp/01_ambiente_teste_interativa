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
            if ($_SESSION["tipo"] == "professor") {
                $idAluno = $_SESSION["id"];
                include "topo.php";
                include "conexao.php";
                $seguranca = new Seguranca();
                $idDisciplina = $seguranca->antisql($_POST["disciplina"]);
                $registros = $seguranca->antisql($_POST["registros"]);
                $tipo = $seguranca->antisql($_POST["tipo"]);
                for($i = 0;$i < $registros;$i++){
                    $nota[$i] = $seguranca->antisql($_POST["txtNota$i"]);
                    $idAluno[$i] = $seguranca->antisql($_POST["idAluno$i"]);
                    $sql = "INSERT INTO nota VALUES(null,'$nota[$i]','$tipo','$idAluno[$i]','$idDisciplina')";
                    mysql_query($sql);
                    
                }
                echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'registros.php';
</script>
";

            }
                
            
                
    } else {
        echo "Acesso negado!;";
        echo "<a href='login.html'>Faça o login!</a>";
    }

?>