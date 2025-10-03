<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>

    <link rel="stylesheet" href="css/cadastro.css">
  <style>
    .table td, .table td * {
      font-size: 1em !important;
    }
  </style>
</head>

<body>
    <?php
    if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "administrador") {
            //conteudo do site
            include "topo.php";
            include 'conexao.php';
    ?>



            <table width="950px" align="center" id="tabelaprincipal">

                <tr>
                    <td>
                        <div id="titulo">
                            <a href="visualizarQuestionario.php" style="float: left" class="button">Visualizar questionários</a>
                            <h3>Cadastro de Disciplina</h3>
                        </div>
                        <form method="post" action="gravarDisciplina.php">
                            <div align="center">
                                <table id="cadastro">
                                    <tr>
                                        <td>Disciplina</td>
                                        <td><input type="text" name="txtDisciplina">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Carga Horária</td>
                                        <td>
                                            <input type="text" name="txtCargaHoraria">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Quantidade de Crédito</td>
                                        <td>
                                            <select name="txtCredito">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Professor
                                        </td>
                                        <td>
                                            <select name="txtProfessor">
                                                <option>::Escolha um Professor::</option>
                                                <?php
                                                $sql        = "SELECT * FROM professor ORDER BY nome";
                                                // echo $sql;
                                                $resultados = mysql_query($sql);
                                                $linhas     = mysql_num_rows($resultados);
                                                if ($linhas > 0) {
                                                    for ($i = 0; $i < $linhas; $i++) {
                                                        $idProfessor = mysql_result($resultados, $i, "idProfessor");
                                                        $nome        = mysql_result($resultados, $i, "nome");
                                                        echo "<option value='$idProfessor'>$nome</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Professor Auxiliar
                                        </td>
                                        <td>
                                            <select name="txtProfessorAuxiliar">
                                                <option value="0">::Escolha um Professor Auxiliar::</option>
                                                <option value="0">Essa disciplina ainda não possui um professor auxiliar</option>
                                                <?php
                                                if ($linhas > 0) {
                                                    for ($i = 0; $i < $linhas; $i++) {
                                                        $idProfessor = mysql_result($resultados, $i, "idProfessor");
                                                        $nome        = mysql_result($resultados, $i, "nome");
                                                        echo "<option value='$idProfessor'>$nome</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Turma
                                        </td>
                                        <td>
                                            <select name="txtTurma">
                                                <option>::Escolha um Turma::</option>
                                                <?php
                                                $sql = "SELECT * FROM turma WHERE semestre = '" . $_SESSION['semestre'] . "' ORDER BY turma";
                                                echo $sql;
                                                $resultados = mysql_query($sql);
                                                $linhas     = mysql_num_rows($resultados);
                                                if ($linhas > 0) {
                                                    for ($i = 0; $i < $linhas; $i++) {
                                                        $idTurma = mysql_result($resultados, $i, "idTurma");
                                                        $turma   = mysql_result($resultados, $i, "turma");
                                                        echo "<option value='$idTurma'>$turma</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Início
                                        </td>
                                        <td><input type="date" name="txtInicio"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Término
                                        </td>
                                        <td><input type="date" name="txtTermino"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type='submit' value='Gravar'>

                                        </td>
                                    </tr>


                                </table>
                            </div>
                        </form>
                        <hr>
                        <center>
                            <b style="width: 100%">Consultar Disciplinas</b><br><br>
                            <div>
                                <form method="get" action="cadastroDisciplina.php" style="width: 50%; float: left">
                                    <select name="txtTurma">
                                        <option>::Escolha um Turma::</option>
                                        <?php
                                        $sql = "SELECT * FROM turma WHERE semestre = '" . $_SESSION['semestre'] . "' ORDER BY turma";
                                        echo $sql;
                                        $resultados = mysql_query($sql);
                                        $linhas     = mysql_num_rows($resultados);
                                        if ($linhas > 0) {
                                            for ($i = 0; $i < $linhas; $i++) {
                                                $idTurma = mysql_result($resultados, $i, "idTurma");
                                                $turma   = mysql_result($resultados, $i, "turma");
                                                echo "<option value='$idTurma'>$turma</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                                </form>

                                <form method="get" action="cadastroDisciplina.php" style="width: 50%; float: left">
                                    <input type="text" name="txtConsulta" placeholder="Digite o nome da disciplina">
                                    <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                                </form>
                            </div>
                        </center>

                        <?php
                        $seguranca = new Seguranca();
                        $consulta  = "";
                        if (isset($_GET["txtConsulta"]) || isset($_GET["txtTurma"])) {
                            if (isset($_GET["txtConsulta"])) {
                                $consulta = $seguranca->antisql($_GET["txtConsulta"]);
                            }

                            $turma = "";
                            if (isset($_GET["txtTurma"])) {
                                $turma = $seguranca->antisql($_GET["txtTurma"]);
                                $turma = " AND turma.idTurma = '$turma'";
                            }

                            $sql        = "select disciplina.*,professor.nome,turma.turma from disciplina,professor,turma where disciplina.idprofessor = professor.idprofessor AND disciplina.idTurma = turma.idTurma AND disciplina.disciplina LIKE '%$consulta%' AND disciplina.semestre = '" . $_SESSION['semestre'] . "' $turma";
                            //echo $sql;
                            $resultados = mysql_query($sql);
                            $linhas     = mysql_num_rows($resultados);
                            if ($linhas > 0) {
                                echo "<table border='0' align='center' id='consulta' class='table' cellpadding='5' cellspacing='0'><tr>
                                        <td style='width:25%;'>Disciplina</td>
                                        <td style='width:5%;'>Carga Horária</td>
                                        <td style='width:5%;'>Crédito</td>
                                        <td style='width:20%;'>Professor</td>
                                        <td style='width:20%;'>Professor Auxiliar</td>
                                        <td style='width:10%;'>Turma</td>
                                        <td style='width:5%;'>Início</td>
                                        <td style='width:5%;'>Término</td>
                                        <td colspan='4' style='width:5%;'>Operações</td>
                                      </tr>";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id           = mysql_result($resultados, $i, "idDisciplina");
                                    $idProfessor  = mysql_result($resultados, $i, "idProfessor");
                                    $nome         = mysql_result($resultados, $i, "nome");
                                    $disciplina   = mysql_result($resultados, $i, "disciplina");
                                    $cargaHoraria = mysql_result($resultados, $i, "cargaHoraria");
                                    $credito      = mysql_result($resultados, $i, "credito");
                                    $idTurma      = mysql_result($resultados, $i, "idTurma");
                                    $turma        = mysql_result($resultados, $i, "turma");
                                    $inicio       = mysql_result($resultados, $i, "inicio");
                                    $termino      = mysql_result($resultados, $i, "termino");

                                    echo "<form method='post' action='operacaoDisciplina.php'>
                      <tr>
                      <td><input type='text' name='txtDisciplina' value='$disciplina'></td>
                      <td><input type='text' name='txtCargaHoraria' value='$cargaHoraria'></td>
                      <td>
                        <select name='txtCredito'>
                            <option " . ($credito == 1 ? 'selected' : '') . " value='1'>1</option>
                            <option " . ($credito == 2 ? 'selected' : '') . " value='2'>2</option>
                            <option " . ($credito == 3 ? 'selected' : '') . " value='3'>3</option>
                            <option " . ($credito == 4 ? 'selected' : '') . " value='4'>4</option>
                            <option " . ($credito == 5 ? 'selected' : '') . " value='5'>5</option>
                        </select>
                      </td>";
                        ?>
                    <td>
                        <select name="txtProfessor" style="font-size: 8px;">
                            <?php
                                    $sql2 = "SELECT idProfessor,nome FROM professor ORDER BY nome";
                                    $resultados2 = mysql_query($sql2);
                                    $linhas2     = mysql_num_rows($resultados2);
                                    echo "<option value='$idProfessor'>$nome</option>";
                                    for ($n = 0; $n < $linhas2; $n++) {
                                        $idProfessor2 = mysql_result($resultados2, $n, "idProfessor");
                                        $professor2   = mysql_result($resultados2, $n, "nome");
                                        if ($idProfessor != $idProfessor2) {
                                            echo "<option value='$idProfessor2'>$professor2</option>";
                                        }
                                    }
                            ?>
                        </select>
                    </td>

                    <td>
                        <select name="txtProfessorAuxiliar" style="font-size: 8px;">
                            <option value="0">::Escolha um Professor Auxiliar::</option>
                            <?php
                                    $resultAux = mysql_query("SELECT professor.idProfessor, nome FROM relacao_professor_auxiliar 
                                        LEFT JOIN professor ON professor.idProfessor = relacao_professor_auxiliar.idProfessor 
                                        WHERE idDisciplina = '$id'");
                                    $linhasAux = mysql_num_rows($resultAux);

                                    if ($linhasAux > 0) {
                                        $idProfessorAux  = mysql_result($resultAux, 0, "idProfessor");
                                        $nomeAux         = mysql_result($resultAux, 0, "nome");
                                        echo '<option value="0">Remover professor auxiliar</option>';
                                        echo "<option selected value='$idProfessorAux'>$nomeAux</option>";
                                    } else {
                                        echo '<option selected value="0">Essa disciplina ainda não possui um professor auxiliar</option>';
                                    }
                                    
                                    for ($n = 0; $n < $linhas2; $n++) {
                                        $idProfessor2 = mysql_result($resultados2, $n, "idProfessor");
                                        $professor2   = mysql_result($resultados2, $n, "nome");
                                        if ($idProfessor != $idProfessor2) {
                                            echo "<option value='$idProfessor2'>$professor2</option>";
                                        }
                                    }
                            ?>
                        </select>
                    </td>


                    <td>
                        <select name="txtTurma" style="font-size: 8px;">

                <?php

                                    $sql2        = "SELECT idTurma,turma FROM turma ORDER BY turma";
                                    //echo $sql;
                                    $resultados2 = mysql_query($sql2);
                                    $linhas2     = mysql_num_rows($resultados2);
                                    echo "<option value='$idTurma'>$turma</option>";
                                    for ($n = 0; $n < $linhas2; $n++) {
                                        $idTurma2 = mysql_result($resultados2, $n, "idTurma");
                                        $turma2   = mysql_result($resultados2, $n, "turma");
                                        if ($idTurma != $idTurma2) {
                                            echo "<option value='$idTurma2'>$turma2</option>";
                                        }
                                    }

                                    echo "</select></td>
                        <td><input type='date' name='txtInicio' value='$inicio'></td>
                        <td><input type='date' name='txtTermino' value='$termino'></td>
                            <input type='hidden' name='id' value='$id'>
                            <input type='hidden' name='operacao' value='alterar'>
                            <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                        </form>";

                                    echo "<form method='post' action='matriculadosDisciplina.php'>
                        <input type='hidden' name='id' value='$id'>
                        <td><input type='image' name='img_atualizar' src='imagens/aluno.png' border='0' style='width: 32px;' title='Alunos matriculados'></td>
                    </form>";

                                    echo "<form method='post' action='questionarioFrequencia.php'>
                        <input type='hidden' name='id' value='$id'>
                        <td><input type='image' name='img_atualizar' src='imagens/kblackbox.png' border='0' style='width: 32px;' title='Questionário de Frequência'></td>
                    </form>";

                                    echo "<form method='post' action='operacaoDisciplina.php'>
                        <input type='hidden' name='id' value='$id'>
                        <input type='hidden' name='operacao' value='excluir'>
                        <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                    </form>
                    </tr>";
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
<?php
        } else {
            echo "Acesso negado!;";
            echo "<a href='login.html'>Faça o login!</a>";
        }
    } else {
        echo "<script>" . "alert('É necessário fazer o login!');" . "window.location='login.html';" . "</script>";
    }
?>