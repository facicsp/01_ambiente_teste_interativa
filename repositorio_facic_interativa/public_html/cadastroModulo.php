<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
    </head>
    <body>
        <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador") {
                //conteudo do site
                include "topo.php";
                ?>

                <table width="950px" align="center" id="tabelaprincipal">

                    <tr>
                        <td>
                            <div id="titulo">
                                <h3>Cadastro de Módulos</h3>
                            </div>
                            <form method="post" action="gravarModulo.php">
                                <div align="center">
                                    <table id="cadastro">
                                        <tr>
                                            <td>
                                                Descrição
                                            </td>
                                            <td>
                                                <input type="text" name="txtDescricao">



                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Curso
                                            </td>
                                            <td>
                                                <select name="txtCurso">
                                                    <option>::Escolha um curso::</option>
                                                    <?php
                                                    include 'conexao.php';
                                                    $sql = "SELECT * FROM curso ORDER BY descricao";
                                                    echo $sql;
                                                    $resultados = mysql_query($sql);
                                                    $linhas = mysql_num_rows($resultados);
                                                    if ($linhas > 0) {
                                                        for ($i = 0; $i < $linhas; $i++) {
                                                            $idCurso = mysql_result($resultados, $i, "idCurso");
                                                            $curso = mysql_result($resultados, $i, "descricao");
                                                            echo "<option value='$idCurso'>$curso</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <input type='image' name='img_gravar' src='imagens/gravar.png' border='0' title='Gravar'>

                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </form>
                            <hr>
                            <center>
                                <form method="get" action="cadastroModulo.php">
                                    <b>Consultar modulos</b><input type="text" name="txtConsulta">
                                    <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                                </form>
                            </center>

                            <?php
                            $consulta = "";
                            if (isset($_GET["txtConsulta"])) {
                                $consulta = $_GET["txtConsulta"];
                            }
                            $sql = "SELECT modulo.*,curso.descricao FROM modulo,curso WHERE modulo LIKE '%$consulta%' and modulo.idcurso = curso.idcurso";
                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);
                            if ($linhas > 0) {
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Descrição</td>
                <td>Curso</td>
                <td colspan='2'>Operações</td>
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idmodulo");
                                    $descricao = mysql_result($resultados, $i, "modulo");
                                    $idCurso = mysql_result($resultados, $i, "idCurso");
                                    $curso = mysql_result($resultados, $i, "descricao");
                                    echo "
                      <form method='post' action='operacaoModulo.php'>
                      <tr>
                      <td>$id</td>
                      <td><input type='text' value='$descricao' name='txtDescricao'></td>
                      <td>";
                                    ?>
                                    <select name="txtCurso">

                                        <?php
                                        include 'conexao.php';
                                        $sql2 = "SELECT * FROM curso ORDER BY descricao";
                                        //echo $sql;
                                        $resultados2 = mysql_query($sql2);
                                        $linhas2 = mysql_num_rows($resultados2);
                                        echo "<option value='$idCurso'>$curso</option>";
                                        for ($n = 0; $n < $linhas2; $n++) {
                                            $idCurso2 = mysql_result($resultados2, $n, "idCurso");
                                            $curso2 = mysql_result($resultados2, $n, "descricao");
                                            if ($idCurso != $idCurso2) {
                                                echo "<option value='$idCurso2'>$curso2</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo "</td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoModulo.php'>
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
                            ?>
                        </td>
                    </tr>

                </table>

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
