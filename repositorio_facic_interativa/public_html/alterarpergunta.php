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

$sql = "SELECT * FROM pergunta WHERE idpergunta = '$id'";

$result = mysql_query($sql);

$linhas = mysql_num_rows($result);

if ($linhas > 0) {
$pergunta = mysql_result($result, 0, "pergunta");
$idDisciplina = mysql_result($result, 0, "idDisciplina");
}
?><div class="principal grid-80 prefix-10 suffix-10">
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Alteração de Pergunta</h3>
                    </div>

                    <form method="post" action="operacaopergunta.php"><div class="grid-100">
                            <label>Pergunta</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtPergunta" value="<?php echo $pergunta;?>">

                        </div><div class="grid-100">
                                <label>Disciplina</label>
                            </div>
                            <div class="grid-100">
                                <select name="txtDisciplina">
                                    <?php
                                    $sqlCombo = "SELECT disciplina FROM disciplina WHERE idDisciplina = '$idDisciplina'";
                                    $resultCombo = mysql_query($sqlCombo);
                                    $descricaoCombo = mysql_result($resultCombo, 0, "disciplina");
                                    ?>
                                    <option value="<?php echo $idDisciplina;?>"><?php echo $descricaoCombo;?></option>
                                    <?php
                                    $idProfessor = $_SESSION["id"];
                                    $sql = "SELECT d.*,t.turma from disciplina d,turma t WHERE d.idProfessor = '$idProfessor' AND d.idTurma = t.idTurma";
                                    $result = mysql_query($sql);
                                    $linhas = mysql_num_rows($result);
                                    if($linhas > 0){
                                        for($i=0;$i<$linhas;$i++){
                                        $idDisciplina = mysql_result($result, $i, "idDisciplina");
                                        $disciplina = mysql_result($result, $i, "disciplina");
                                        $turma = mysql_result($result, $i, "turma");
                                        echo "<option value='$idDisciplina'>$disciplina - $turma</option>";
                                        }
                                    }
                                    
                                ?>    
                                </select>

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