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
            if ($_SESSION["tipo"] == "administrador") {
                //conteudo do site
                include "topo.php";
                
include 'conexao.php';
 
$seguranca = new Seguranca();

include './Util.php';

$util = new Util();

$id = $seguranca->antisql($_POST["id"]);

$sql = "SELECT * FROM area WHERE idarea = '$id'";

$result = mysql_query($sql);

$linhas = mysql_num_rows($result);

if ($linhas > 0) {
$area = mysql_result($result, 0, "area");
}
?><div class="principal grid-80 prefix-10 suffix-10">
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Alteração de Area</h3>
                    </div>

                    <form method="post" action="operacaoarea.php"><div class="grid-100">
                            <label>Area</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtArea" value="<?php echo $area;?>" maxlength="100">

                        </div><div class="grid-100" style="margin-top: 20px;">
                            <input type="hidden" name="operacao" value="alterar">
                            <input type="hidden" name="id" value="<?php echo $id;?>">
                            <input type='submit' value='Alterar' class="botaoform">                 </form>
                    <br><hr></body>
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