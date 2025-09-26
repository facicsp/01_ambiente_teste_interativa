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
            if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
                //conteudo do site
                include "topo.php";
                
include 'conexao.php';
 
$seguranca = new Seguranca();

include './Util.php';

$util = new Util();

$id = $seguranca->antisql($_POST["id"]);

$sql = "SELECT * FROM resposta WHERE idresposta = '$id'";

$result = mysql_query($sql);

$linhas = mysql_num_rows($result);

if ($linhas > 0) {
$resposta = mysql_result($result, 0, "resposta");
$correta = mysql_result($result, 0, "correta");
$idPergunta = mysql_result($result, 0, "idPergunta");
}
?><div class="principal grid-80 prefix-10 suffix-10">
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Alteração de Resposta</h3>
                    </div>

                    <form method="post" action="operacaoresposta.php"><div class="grid-100">
                            <label>Resposta</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtResposta" value="<?php echo $resposta;?>">

                        </div><div class="grid-100">
                            <label>Correta</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtCorreta">
                                <option value="<?php echo $correta;?>"><?php echo $correta;?></option>
                                <option value="sim">Sim</option>
                                <option value="nao">Não</option>

                            </select>
                        </div>
                        <!--<div class="grid-100">
                                <label>Pergunta</label>
                            </div>
                            <div class="grid-100">
                                <select name="txtIdPergunta">-->
                                    <?php
                                    /*
                                    $sqlCombo = "SELECT pergunta FROM Pergunta WHERE IdPergunta = '$IdPergunta'";
                                    $resultCombo = mysql_query($sqlCombo);
                                    $descricaoCombo = mysql_result($resultCombo, 0, "pergunta");
                                     * 
                                     */
                                    ?>

                                    <!--<option value="<?php //echo $IdPergunta;?>"><?php //echo $descricaoCombo;?></option>-->
                                    <?php
                                    
                                    /*
                                    $dados = $util->carregarCombo("Pergunta", "IdPergunta", "pergunta");
                                    for($i=0;$i < sizeof($dados);$i++){
                                    echo "<option value='".$dados[$i][0]."'>".$dados[$i][1]."</option>";
                                    }*/
                                ?>    
                                <!--</select>-->

                            </div><div class="grid-100" style="margin-top: 20px;">
                            <input type="hidden" name="operacao" value="alterar">
                            <input type="hidden" name="id" value="<?php echo $id;?>">
                            <input type='submit' value='Alterar' class="botaoform">                 
                            </form><input type="button" value="Voltar" onclick="history.back()">
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