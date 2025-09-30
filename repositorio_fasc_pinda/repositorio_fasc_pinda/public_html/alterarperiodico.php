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
                
include 'LoginRestrito/conexao.php';
 
$seguranca = new Seguranca();

include './Util.php';

$util = new Util();

$id = $seguranca->antisql($_POST["id"]);

$sql = "SELECT p.*,a.area FROM periodico p,area a WHERE p.idperiodico = '$id' AND p.idarea = a.idarea ";

$result = mysqli_query($conexao, $sql);

$linhas = mysqli_num_rows($result);

if ($linhas > 0) {
$titulo = mysql_result($result, 0, "titulo");
$link = mysql_result($result, 0, "link");
$idarea = mysql_result($result, 0, "idarea");
$area = mysql_result($result, 0, "area");
}
?><div class="principal grid-80 prefix-10 suffix-10">
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Alteração de Periódico</h3>
                    </div>

                    <form method="post" action="operacaoperiodico.php"><div class="grid-100">
                            <label>Título</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtTitulo" value="<?php echo $titulo;?>">

                        </div><div class="grid-100">
                            <label>Link</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtLink" value="<?php echo $link;?>">

                        </div>
                        <div class="grid-100">
                            <label>Área de Conhecimento</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtArea">
                                <option value="<?php echo $idarea;?>"><?php echo $area;?></option>
                                <?php
                                $dados = $util->carregarCombo("area", "idarea", "area");
                                for($i=0;$i<sizeof($dados);$i++){
                                    if($dados[$i][0] != $idarea){
                                    echo "<option value=".$dados[$i][0].">".$dados[$i][1]."</option>";
                                    }
                                }
                                ?>
                            </select>
                        
                        <div class="grid-100" style="margin-top: 20px;">
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