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
                ?>

                <div class="principal grid-80 prefix-10 suffix-10">
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Cadastro de Medalhas</h3>
                    </div>
                    <div class="grid-50">
                    <form method="post" action="gravarMedalha.php" enctype="multipart/form-data">
                        <div class="grid-100">
                            <label>Descrição</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtDescricao">

                        </div>
                        
                        <div class="grid-100">
                            <label>Pontos</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtPontos">

                        </div>
                        
                        <div class="grid-100">
                            <label>Medalha</label>
                        </div>
                        <div class="grid-100">
                            <input type="file" name="arquivo">

                        </div>
                        
                        <div class="grid-100">
                            <label>Observação</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtObs">

                        </div>
                        
                        <div class="grid-100">
                            <label>Disciplina</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtDisciplina">
                                <?php
                                    include './conexao.php';
                                    include './Util.php';
                                    $util = new Util();
                                    $dados = $util->carregarCombo("disciplina", "idDisciplina", "disciplina");
                                    for($i=0;$i < sizeof($dados);$i++){
                                    echo "<option value='".$dados[$i][0]."'>".$dados[$i][1]."</option>";
                                    }
                                ?>
                            </select>
                            
                        </div>
                        
                        <div class="grid-100" style="margin-top: 20px;">
                            <input type='image' name='img_gravar' src='imagens/gravar.png' border='0' title='Gravar'>
                        </div>
                    </form>
                        </div>
                    <div class="grid-50">
                        <form method="post" action="duplicarMedalha.php">
                        <p>Para duplicar um sistema de medalhas, escolha as opções abaixo:</p>
                        <div class="grid-100">
                            <label>Escolha a disciplina que já possui um sistema criado:</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtDisciplina1">
                                <?php
                                    $dados = $util->carregarCombo("disciplina", "idDisciplina", "disciplina");
                                    for($i=0;$i < sizeof($dados);$i++){
                                    echo "<option value='".$dados[$i][0]."'>".$dados[$i][1]."</option>";
                                    }
                                ?>
                            </select>
                            
                        </div>
                        <hr>
                        <div class="grid-100">
                            <label>Escolha a disciplina que irá receber os dados de duplicação:</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtDisciplina2">
                                <?php
                                    $dados = $util->carregarCombo("disciplina", "idDisciplina", "disciplina");
                                    for($i=0;$i < sizeof($dados);$i++){
                                    echo "<option value='".$dados[$i][0]."'>".$dados[$i][1]."</option>";
                                    }
                                ?>
                            </select>
                            
                        </div>
                        <input type="submit" value="Duplicar">
                        </form>
                    </div>
                    <br><hr>
                    <div class="grid-100">
                        <label>Consultar Medalha</label>
                    </div>
                    <div class="grid-100">
                        <form method="get" action="cadastroMedalha.php">
                            <input type="text" name="txtConsulta">
                                
                            </select>
                        <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                        </form>
                    </div>
                    
                    <div class="grid-100" style="margin-top:30px;">
                        <?php
                        
                        $consulta = "";
                        if (isset($_GET["txtConsulta"])) {
                            $consulta = $_GET["txtConsulta"];
                            
                            $sql = "SELECT medalha.*,disciplina.disciplina as disciplina FROM medalha,disciplina WHERE medalha.descricao LIKE '%$consulta%' AND medalha.idDisciplina = disciplina.idDisciplina";
                            //echo $sql;
                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);
                            if ($linhas > 0) {
                      
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Descrição</td>
                <td>Pontos</td>
                <td>Observação</td>
                <td>Medalha</td>
                <td>Disciplina</td>
                <td colspan='2'>Operações</td>
                
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idMedalha");
                                    $descricao = mysql_result($resultados, $i, "descricao");
                                    $pontos = mysql_result($resultados, $i, "pontos");
                                    $obs = mysql_result($resultados, $i, "obs");
                                    $medalha = mysql_result($resultados, $i, "medalha");
                                    $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                                    $disciplina = mysql_result($resultados, $i, "disciplina");
                                    echo "
                      <form method='post' action='operacaoMedalha.php' enctype='multipart/form-data'>
                      <tr>
                      <td>$id</td>
                      <td><input type='text' value='$descricao' name='txtDescricao'></td>
                      <td><input type='text' value='$pontos' name='txtPontos'></td>    
                      <td><input type='text' value='$obs' name='txtObs'></td>
                      <td class='medalha'><input type='file' name='arquivo'><img src='medalha/$medalha'></td>
                      <td><select name='txtDisciplina'>
                      <option value='$idDisciplina'>$disciplina</option>";
                                  for($j=0;$j < sizeof($dados);$j++){
                                    echo "<option value='".$dados[$j][0]."'>".$dados[$j][1]."</option>";
                                    }


                    echo "</td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoMedalha.php'>
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