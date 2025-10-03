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
                include './Util.php';
                $util = new Util();
                include './conexao.php';
                $seguranca = new Seguranca();
                if(isset($_GET["idPergunta"])){
                    $idPergunta = $seguranca->antisql($_GET["idPergunta"]);
                    $pergunta = $seguranca->antisql($_GET["pergunta"]);
                    $_SESSION["idPergunta"] = $idPergunta;
                    $_SESSION["pergunta"] = $pergunta;
                            
                }
                ?>
<div class="principal grid-80 prefix-10 suffix-10">
    <a href="cadastropergunta.php">Voltar para as Perguntas</a>
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Cadastro de Resposta</h3>
                    </div>

                    <form method="post" action="gravarresposta.php"><div class="grid-100">
                            <label>Resposta</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtResposta">

                        </div><div class="grid-100">
                            <label>Correta</label>
                        </div>
                        <div class="grid-100">
                            
                            <select name="txtCorreta">
                                
                                <option value="nao">Não</option>
                                <option value="sim">Sim</option>
                                
                            </select>

                        </div><div class="grid-100">
                                <label>Pergunta</label>
                            </div>
                            <div class="grid-100">
                                <select name="txtIdPergunta">
                                    
                                    <?php
                                    if(isset($_SESSION["idPergunta"])){
                                        $idPergunta = $_SESSION["idPergunta"];
                                        $pergunta = $_SESSION["pergunta"];
                                        echo "<option value='$idPergunta'>$pergunta</option>";
                                    }else{
                                        //echo "<option value=\"\">Escolha a opção</option>";
                                    }
                                    /*
                                    $dados = $util->carregarCombo("Pergunta", "IdPergunta", "pergunta");
                                    for($i=0;$i < sizeof($dados);$i++){
                                    echo "<option value='".$dados[$i][0]."'>".$dados[$i][1]."</option>";
                                    }*/
                                ?>    
                                </select>

                            </div><div class="grid-100" style="margin-top: 20px;">
                            <input type='submit' value='Gravar' class="botaoform">                 </form>
                    <br><hr>
                    <div class="grid-100">
                        <label>Consultar resposta</label>
                    </div>
                    <div class="grid-100">
                        <form method="get" action="cadastroresposta.php">
                            <input type="text" name="txtConsulta">
                                
                            </select>
                        <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                        </form>
                    </div>        <div class="grid-100" style="margin-top:30px;">
                        <?php
                        
                        $consulta = "";
                        if (isset($_GET["txtConsulta"])) {
                            $consulta = $_GET["txtConsulta"];
                            
                            //$sql = "SELECT * FROM resposta WHERE resposta.resposta LIKE '%$consulta%' ";
                            $sql = "SELECT * FROM resposta WHERE idPergunta = '$idPergunta' ";
                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);
                            if ($linhas > 0) {
                      
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Descrição</td>
                <td>Alternativa correta?</td>
                <td colspan='2'>Operações</td>
                
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idresposta");
                                    $descricao = mysql_result($resultados, $i, "resposta");
                                    $correta = mysql_result($resultados, $i, "correta");
                                    echo "
                      <form method='post' action='alterarresposta.php'>
                      <tr>
                      <td>$id</td>
                      <td><input type='text' value='$descricao' name='txtDescricao'></td>
                      <td>$correta</td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoresposta.php'>
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