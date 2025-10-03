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
                
                if(isset($_POST["idExtra"])){
                    include "topo.php";
                include "conexao.php";
                $seguranca = new Seguranca();
                $idExtra = $seguranca->antisql($_POST["idExtra"]);
                $pontos = $seguranca->antisql($_POST["txtPontos"]);
                $sql = "UPDATE extra SET pontos = '$pontos' WHERE idExtra = '$idExtra'";
                mysql_query($sql);
                
                
                echo "<script>

window.history.back();
</script>
";

            }
            }
            
                
    } else {
        echo "Acesso negado!;";
        echo "<a href='login.html'>Faça o login!</a>";
    }

?>