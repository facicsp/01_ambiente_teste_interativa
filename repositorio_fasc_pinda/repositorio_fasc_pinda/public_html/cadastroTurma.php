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
                //atividade do site
                include "topo.php";include "conexao.php";
                $seguranca = new Seguranca();
?>

        <table width="950px" align="center" id="tabelaprincipal">

            <tr>
                <td>
                    <div id="titulo">
                        <h3>Cadastro de Turmas</h3>
                    </div>
                    <form method="post" action="gravarTurma.php">
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
                                        <input type='submit' value='Gravar'>
                                        
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </form>
                    <hr>
                    <center>
                        <form method="get" action="cadastroTurma.php">
                            <b>Consultar turmas</b><input type="text" name="txtConsulta">
                            <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                        </form>
                    </center>

                    <?php
                    $datahoje = date("Y-m-d");
                    $consulta = "";
                    if (isset($_GET["txtConsulta"])) {
                        $consulta = $_GET["txtConsulta"];
                    
                    $sql = "SELECT turma.*,curso.descricao FROM turma,curso WHERE turma LIKE '%$consulta%' and turma.idcurso = curso.idcurso and semestre = '".$_SESSION['semestre']."'";
                    //echo $sql;
                    $resultados = mysql_query($sql);
                    $linhas = mysql_num_rows($resultados);
                    if ($linhas > 0) {
                        echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Descrição</td>
                <td>Curso</td>
                <td>Semestre</td>
                <td colspan='3'>Operações</td>
                <td colspan='2'>Relatórios</td>
                </tr>
";

                        for ($i = 0; $i < $linhas; $i++) {
                            $id = mysql_result($resultados, $i, "idturma");
                            $descricao = mysql_result($resultados, $i, "turma");
                            $idCurso = mysql_result($resultados, $i, "idCurso");
                            $curso = mysql_result($resultados, $i, "descricao");
                            $semestre = mysql_result($resultados, $i, "semestre");
                            echo "
                      <form method='post' action='operacaoTurma.php'>
                      <tr>
                      <td>$id</td>
                      <td><input type='text' value='$descricao' name='txtDescricao'></td>
                      <td>";
                            ?>
                            <select name="txtCurso">

                                <?php
                                
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
                                $semana = "2020-04-06";
                                
                                echo "</td>
                                <td>
                                    <select name='txtSemestre'>
                                        <option value='$semestre'>$semestre</option>
                                        <option value='2025/2'>2025/2</option>
                                        <option value='2025/1'>2025/1</option>
                                        <option value='2024/2'>2024/2</option>
                                        <option value='2024/1'>2024/1</option>
                                        <option value='2023/2'>2023/2</option>
                                        <option value='2023/1'>2023/1</option>
                                        <option value='2022/2'>2022/2</option>
                                        <option value='2022/1'>2022/1</option>
                                        <option value='2021/1'>2021/1</option>
                                        <option value='2021/2'>2021/2</option>
                                        <option value='2020/2'>2020/2</option>
                                        <option value='2020/1'>2020/1</option>
                                        <option value='2019/2'>2019/2</option>
                                        <option value='2019/1'>2019/1</option>
                                    </select>
                                </td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoTurma.php'>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='excluir'>
                      <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                      </form>
                      <td><a href='matriculados.php?idturma=$id&turma=$descricao'>Ver Matriculados</a></td>
                      <td>
                      <form method='post' action='acessosalunos.php' target='_blank'>
                      <label>Data dos Acessos:</label><input type='date' name='txtData' value='$datahoje'>
                      <input type='hidden' name='idTurma' value='$id'>
                      <input type='hidden' name='turma' value='$descricao'>
                      <input type='submit' value='Relatório de Acessos'>
                      </form>
                      </td>
                      <td>
                      <form method='post' action='acessosalunossemanal.php' target='_blank'>
                      <label>Semana:</label>
                      <select name='txtSemana'>";
                      while($semana < "2020-12-28"){
                                $semana = date('Y-m-d', strtotime("+7 days", strtotime($semana)));
                                $semana2 = date('d/m/Y', strtotime("+0 days", strtotime($semana)));
                                echo "<option value='$semana'>Semana de $semana2</option>";
                                }
                      echo "</select>
                      <input type='hidden' name='idTurma' value='$id'>
                      <input type='hidden' name='turma' value='$descricao'>
                      <input type='submit' value='Acesso Semanal'>
                      </form>
                      </td>
                      </tr>
";
                            }

                            echo "</table>";
                        } else {
                            echo "Nenhuma registro encontrado.";
                        }
                    }
                        ?>
                </td>
            </tr>

        </table>

    </body>
</html>
 <?php } else {
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