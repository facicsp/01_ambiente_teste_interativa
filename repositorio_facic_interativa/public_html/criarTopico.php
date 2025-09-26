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
            if ($_SESSION["tipo"] == "aluno") {
                $idAluno = $_SESSION["id"];
                include "topo.php";
                ?>


        <div class="dados">
            <div class="barratitulo"><h1>Criar Tópico</h1></div>
                        <?php
                        include "conexao.php";
                        $seguranca = new Seguranca();
                        $idUsuario =$_SESSION["id"];
                        $tipo = $_SESSION["tipo"];
                        $idDisciplina = $seguranca->antisql($_POST["idDisciplina"]);
                        $disciplina = $seguranca->antisql($_POST["disciplina"]);
                        
                        echo "<form method='post' action='gravarTopico.php'>";

                        
                        
                        ?>

                </table>
                <hr>
  <div class="voltar"><a href="index.php"><i class="icon small rounded color1 fa-arrow-left"></i> Voltar</a></div>
                
            </div>
        </form>
        <hr>


        </body>
        </html>
        <?php
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