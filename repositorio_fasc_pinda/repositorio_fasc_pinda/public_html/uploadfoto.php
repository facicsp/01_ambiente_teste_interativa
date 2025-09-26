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
                ?>


        <div class="dados">
            <div class="barratitulo"><h1>Sua postagem terá fotos? Se sim, faça o upload delas:</h1></div>
            <form method="post" action="salvarfoto.php"  enctype="multipart/form-data">
                <label>Foto</label>

                <input type="file" name="arquivo"/><br><br>
                <input type="submit" value="Salvar foto">
            </form>
            <br>
            <hr>
            <form method="post" action="cadastromural.php">
                <input type="submit" value="Continuar Postagem">
            </form>
                
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