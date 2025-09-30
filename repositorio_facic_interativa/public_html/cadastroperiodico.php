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
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Cadastro de Periódico</h3>
                    </div>

                    <form method="post" action="gravarperiodico.php"><div class="grid-100">
                            <label>Título</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtTitulo">

                        </div><div class="grid-100">
                            <label>Link</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtLink">

                        </div>
                        <div class="grid-100">
                            <label>Área de Conhecimento</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtArea">
                                <?php
                                $dados = $util->carregarCombo("area", "idarea", "area");
                                for($i=0;$i<sizeof($dados);$i++){
                                    echo "<option value=".$dados[$i][0].">".$dados[$i][1]."</option>";
                                }
                                ?>
                            </select>
                            <a href="cadastroarea.php"><img src="imagens/add.png">Adicionar Nova Área de Conhecimento</a>

                        </div>
                        <div class="grid-100" style="margin-top: 20px;">
                            <input type='submit' value='Gravar' class="botaoform">                 </form>
                    <br><hr>
                    <div class="grid-100">
                        <label>Consultar periódico</label>
                    </div>
                    <div class="grid-100">
                        <form method="get" action="cadastroperiodico.php">
                            <input type="text" name="txtConsulta">
                                
                            </select>
                        <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                        </form>
                    </div>        <div class="grid-100" style="margin-top:30px;">
                        <?php
                        
                        $consulta = "";
                        if (isset($_GET["txtConsulta"])) {
                            $consulta = $_GET["txtConsulta"];
                            
                            $sql = "SELECT p.*,a.area FROM periodico p,area a WHERE p.titulo LIKE '%$consulta%' AND p.idarea = a.idarea ORDER BY p.titulo";
                            $resultados = mysqli_query($conexao, $sql);
                            $linhas = mysqli_num_rows($resultados);
                            if ($linhas > 0) {
                      
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Título</td>
                <td>Link</td>
                <td>Área</td>
                <td colspan='2'>Operações</td>
                
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idperiodico");
                                    $descricao = mysql_result($resultados, $i, "titulo");
                                    $link = mysql_result($resultados, $i, "link");
                                    $area = mysql_result($resultados,$i,"area");
                                    echo "
                      <form method='post' action='alterarperiodico.php'>
                      <tr>
                      <td>$id</td>
                      <td>$descricao</td>
                      <td>$link</td>
                      <td>$area</td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoperiodico.php'>
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