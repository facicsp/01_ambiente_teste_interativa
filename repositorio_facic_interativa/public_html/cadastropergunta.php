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
            if ($_SESSION["tipo"] == "administrador"  || $_SESSION["tipo"] == "professor") {
                //conteudo do site
                include "topo.php";
                include './Util.php';
                $util = new Util();
                include './conexao.php';
                ?>
<div class="principal grid-80 prefix-10 suffix-10">
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Cadastro de Pergunta</h3>
                    </div>

                    <form method="post" action="gravarpergunta.php"><div class="grid-100">
                            <label>Pergunta</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtPergunta">

                        </div><div class="grid-100">
                                <label>Disciplina</label>
                            </div>
                            <div class="grid-100">
                                <select name="txtIdDisciplina">
                                    <option value="">Escolha a opção</option>
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
                            <input type='submit' value='Gravar' class="botaoform">                 </form>
                    <br><hr>
                    <div class="grid-100">
                        <label>Consultar pergunta</label>
                    </div>
                    <div class="grid-100">
                        <form method="get" action="cadastropergunta.php">
                            <input type="text" name="txtConsulta">
                                
                            </select>
                        <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                        </form>
                    </div>        <div class="grid-100" style="margin-top:30px;">
                        <?php
                        
                        $consulta = "";
                        if (isset($_GET["txtConsulta"])) {
                            $consulta = $_GET["txtConsulta"];
                            $idProfessor = $_SESSION["id"];
                            $sql = "SELECT p.* FROM pergunta p,disciplina d WHERE p.pergunta LIKE '%$consulta%' and p.idDisciplina = d.idDisciplina and d.idProfessor = '$idProfessor'";
                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);
                            if ($linhas > 0) {
                      
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td style='width:5px;'>Código</td>
                <td style='width:5px;'>Respostas</td>
                <td>Descrição</td>
                <td colspan='2'>Operações</td>
                
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idpergunta");
                                    $descricao = mysql_result($resultados, $i, "pergunta");
                                    echo "
                      <form method='post' action='alterarpergunta.php'>
                      <tr>
                      <td style='width:5px;'>$id</td>
                      <td style='width:5px;'><a href='cadastroresposta.php?idPergunta=$id&pergunta=$descricao'><img src='imagens/notaPequeno.png'></a></td>
                      <td><input type='text' value='$descricao' name='txtDescricao'></td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaopergunta.php'>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='excluir'>
                      <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                      </form>
                      
                      </tr>
";
                                }

                                echo "</table>";
                            } else {
                                echo "Nenhuma registro encontrado.";
                            }
                        }
                        ?>

                    </div>                    
                </div>

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