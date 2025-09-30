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
                include './Util.php';
                $util = new Util();
                include 'LoginRestrito/conexao.php';
                ?>
<div class="principal grid-80 prefix-10 suffix-10">
    <a href="cadastroperiodico.php">Voltar para Periódicos</a>
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Cadastro de Area</h3>
                    </div>

                    <form method="post" action="gravararea.php"><div class="grid-100">
                            <label>Area</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtArea">

                        </div><div class="grid-100" style="margin-top: 20px;">
                            <input type='submit' value='Gravar' class="botaoform">                 </form>
                    <br><hr>
                    <div class="grid-100">
                        <label>Consultar area</label>
                    </div>
                    <div class="grid-100">
                        <form method="get" action="cadastroarea.php">
                            <input type="text" name="txtConsulta">
                                
                            </select>
                        <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                        </form>
                    </div>        <div class="grid-100" style="margin-top:30px;">
                        <?php
                        
                        $consulta = "";
                        if (isset($_GET["txtConsulta"])) {
                            $consulta = $_GET["txtConsulta"];
                            
                            $sql = "SELECT * FROM area WHERE area.area LIKE '%$consulta%' ";
                            $resultados = mysqli_query($conexao, $sql);
                            $linhas = mysqli_num_rows($resultados);
                            if ($linhas > 0) {
                      
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Descrição</td>
                <td colspan='2'>Operações</td>
                
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idarea");
                                    $descricao = mysql_result($resultados, $i, "area");
                                    echo "
                      <form method='post' action='alterararea.php'>
                      <tr>
                      <td>$id</td>
                      <td><input type='text' value='$descricao' name='txtDescricao'></td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoarea.php'>
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