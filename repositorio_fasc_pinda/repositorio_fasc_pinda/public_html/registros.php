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
        if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "administrador") {
            $idProfessor = $_SESSION["id"];
            include "topo.php";
    ?>


            <div class="dados">
                <div class="barratitulo">
                    <h1>Minhas Aulas</h1>
                </div>
                <?php
                include "LoginRestrito/conexao.php";
                include "funcaoDisciplinas.php";
                $seguranca = new Seguranca();

                if ($_SESSION["tipo"] == "professor") {
                    $idProfessor = $_SESSION["id"];
                    $sql = sqlDisciplina($idProfessor, $_SESSION['semestre']);
                } else if ($_SESSION["tipo"] == "administrador") {
                    $sql = "select disciplina.*,turma.turma,professor.nome from disciplina,turma,professor where disciplina.semestre = '" . $_SESSION['semestre'] . "' AND disciplina.idprofessor = professor.idprofessor and disciplina.idturma = turma.idturma ORDER BY idTurma";
                }
                
                $result = mysqli_query($conexao, $sql);
                $linhas = mysqli_num_rows($result);
                if ($linhas > 0) {
                    $idTurma2 = "";
                    for ($i = 0; $i < $linhas; $i++) {
                        $idDisciplina = mysql_result($result, $i, "idDisciplina");
                        $disciplina = mysql_result($result, $i, "disciplina");
                        $cargaHoraria = mysql_result($result, $i, "cargaHoraria");
                        $idProfessor = mysql_result($result, $i, "idProfessor");
                        $idTurma = mysql_result($result, $i, "idTurma");
                        $turma = mysql_result($result, $i, "turma");
                        if ($_SESSION["tipo"] == "administrador") {
                            $professor = mysql_result($result, $i, "nome");
                        }
                        $_SESSION["idDisciplina"][$i] = $idDisciplina;
                        $_SESSION["disciplina"][$i] = $disciplina;
                        $_SESSION["idProfessor"][$i] = $idProfessor;
                        if ($idTurma != $idTurma2 || $i == 0) {

                            echo "<hr><div class='turma'>|:|Turma: $turma</div>";
                        }
                        if ($_SESSION["tipo"] == "administrador") {
                            echo "<div class='lista'><p>Professor:$professor - $disciplina :: ";
                            echo "<a href='exibirNotasProfessor.php?id=$i'><img src='imagens/notaPequeno.png'>Visualizar Notas</a>";
                            echo "<a href='exibirFrequenciaProfessor.php?id=$i'><img src='imagens/frequenciaPequeno.png'>Visualizar Frequência</a>"
                                . "</p></div>";
                        } else if ($_SESSION["tipo"] == "professor") {
                            echo "<div class='lista'><p>$disciplina :: ";
                            //echo "<a href='exibirNotasProfessor.php?id=$i'><img src='imagens/notaPequeno.png'>Visualizar Notas</a>"
                            echo "<a href='dadosTurma.php?idDisciplina=$i'><img src='imagens/notaPequeno.png'>Visualizar Notas</a>";
                            echo "<a href='exibirFrequenciaProfessor.php?id=$i'><img src='imagens/frequenciaPequeno.png'>Visualizar Frequência</a>"
                                . "</p></div>";
                        }


                        $idTurma2 = $idTurma;
                    }
                } else {
                    echo "Nenhuma registro encontrado.";
                }
                ?>

                </table>
                <hr>
                <div class="voltar"><a href="index.php"><img src="imagens/voltar.png">Voltar</a></div>

            </div>
            </form>
            <hr>


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