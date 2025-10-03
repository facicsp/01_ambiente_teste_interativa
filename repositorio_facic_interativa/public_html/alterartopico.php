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
            if ($_SESSION["tipo"] == "professor") {
                //conteudo do site
                include "topo.php";
                
include 'conexao.php';
 
$seguranca = new Seguranca();

include './Util.php';

$util = new Util();

$id = $seguranca->antisql($_POST["id"]);

$sql = "SELECT t.*,d.disciplina FROM topico t,disciplina d WHERE idtopico = '$id' AND t.idDisciplina = d.idDisciplina";

$result = mysql_query($sql);

$linhas = mysql_num_rows($result);

if ($linhas > 0) {
$idUsuario = mysql_result($result, 0, "idUsuario");
$tipo = mysql_result($result, 0, "tipo");
$titulo = mysql_result($result, 0, "titulo");
$conteudo = mysql_result($result, 0, "conteudo");
$status = mysql_result($result, 0, "status");
$idDirecionado = mysql_result($result, 0, "idDirecionado");
$idTurma = mysql_result($result, 0, "idTurma");
$idDisciplina = mysql_result($result, 0, "idDisciplina");
$disciplina = mysql_result($result, 0, "disciplina");
}
?><div class="principal grid-80 prefix-10 suffix-10">
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Alteração de Topico</h3>
                    </div>

                    <form method="post" action="operacaotopico.php"><div class="grid-100">
                            <div class="grid-100">
                            <label>Titulo</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtTitulo" value="<?php echo $titulo;?>">

                        </div><div class="grid-100">
                            <label>Conteudo</label>
                        </div>
                        <div class="grid-100">
                            <textarea name="txtConteudo" cols="80" rows="5"><?php echo $conteudo;?></textarea>

                        </div><div class="grid-100">
                            <label>Status</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtStatus" value="<?php echo $status;?>" maxlength="0">

                        </div><div class="grid-100">
                            <label>IdDisciplina</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtIdDisciplina">
                                <?php echo "<option value='$idDisciplina'>$disciplina</option>";?>

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