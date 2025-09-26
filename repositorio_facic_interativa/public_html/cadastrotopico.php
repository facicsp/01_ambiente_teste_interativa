<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/cadastro.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        function isAvaliativo(el) {
            if (el.value == 1)
                $('.isAvaliativo').removeClass('hidden')
            else
                $('.isAvaliativo').addClass('hidden')
        }
    </script>

    <style>
        .hidden {
            display: none;
        }
    </style>

</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "professor") {
            //conteudo do site
            include "topo.php";
            include './Util.php';
            $util = new Util();
            include './conexao.php';
            $seguranca = new Seguranca();
            $idUsuario = $_SESSION["id"];
            $tipo = $_SESSION["tipo"];
            $idDisciplina = 0;
            $disciplina = "Toda a turma ou escolha abaixo a disciplina específica";
            $listaDisciplina = "";
            if (isset($_POST["disciplina"])) {
                $idDisciplina = $seguranca->antisql($_POST["idDisciplina"]);
                $disciplina = $seguranca->antisql($_POST["disciplina"]);

                $listaDisciplina = "<option value=\"$idDisciplina\">$disciplina</option>";
            } else {
                if ($tipo == "aluno") {
                    $sql = "select disciplina.*,professor.nome from disciplina,professor 
where idturma in(select idturma from matricula where idaluno = '$idUsuario') 
AND disciplina.semestre = '" . $_SESSION['semestre'] . "' 
AND disciplina.idProfessor = professor.idProfessor ORDER BY disciplina.disciplina";
                } else if ($tipo == "professor") {
                    //$sql =  "select * from disciplina WHERE disciplina.semestre = '". $_SESSION['semestre']."' AND idProfessor = '$idUsuario' ORDER BY disciplina";
                    include "funcaoDisciplinas.php";
                    $sql = sqlDisciplina($idUsuario, $_SESSION['semestre']);
                }
                
                $result = mysql_query($sql);
                $linhas = mysql_num_rows($result);
                if ($linhas > 0) {
                    for ($i = 0; $i < $linhas; $i++) {
                        $idDisciplina = mysql_result($result, $i, "idDisciplina");
                        $disciplina = mysql_result($result, $i, "disciplina");
                        $turma = mysql_result($result, $i, "turma");
                        $listaDisciplina .= "<option value='$idDisciplina'>$turma - $disciplina</option>";
                    }
                }
            }

            $listaDisciplina .= "<option value='0'>Toda a turma.</option>";


            if (isset($_GET["idDisciplina"])) $idDisciplina = $_GET["idDisciplina"];
            else $idDisciplina = "";

    ?>
            <div class="grid-100">
                <label>Consultar topico</label>
            </div>
            <div class="grid-100">
                <form method="get" action="cadastrotopico.php">
                    <input type="text" name="txtConsulta">
                    <input type="hidden" value="<?= $idDisciplina ?>" name="idDisciplina">

                    </select>
                    <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                </form>
                <div class="grid-100" style="margin-top:30px;">
                    <?php
                    $consulta = "";
                    if (isset($_GET["txtConsulta"])) {
                        $consulta = $_GET["txtConsulta"];

                        if ($idDisciplina != '') {
                            $sql = "SELECT * FROM topico WHERE idDisciplina = '$idDisciplina'";
                        } else {
                            $sql = "SELECT * FROM topico WHERE idUsuario = $idUsuario AND (titulo LIKE '%$consulta%' OR conteudo LIKE '%$consulta%') ";
                        }

                        $resultados = mysql_query($sql);
                        $linhas = mysql_num_rows($resultados);
                        if ($linhas > 0) {

                            echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Descrição</td>
                <td>Avaliativo</td>
                <td colspan='2'>Operações</td>
                
                </tr>
";

                            for ($i = 0; $i < $linhas; $i++) {
                                $id = mysql_result($resultados, $i, "idtopico");
                                $descricao = mysql_result($resultados, $i, "titulo");
                                $idDisciplina = mysql_result($resultados, $i, "idDisciplina");

                                $resultDisciplina = mysql_query("SELECT * FROM disciplina WHERE idDisciplina = $idDisciplina");
                                $semestre = mysql_result($resultDisciplina, 0, 'semestre');

                                $resultAvaliativo = mysql_query("SELECT bimestre FROM forumavaliacao WHERE idTopico = '$id'");

                                if (mysql_num_rows($resultAvaliativo) > 0) {
                                    $avaliativo = mysql_result($resultAvaliativo, 0, "bimestre") . "º Bimestre";
                                } else {
                                    $avaliativo = "Não avaliativo";
                                }

                                if ($semestre == $_SESSION['semestre']) {

                                    echo "<form method='post' action='alterartopico.php'>
                                            <tr>
                                            <td>$id</td>
                                            <td><input type='text' value='$descricao' name='txtDescricao'></td>
                                            <td>$avaliativo</td>
                                            <input type='hidden' name='id' value='$id'>
                                            <input type='hidden' name='operacao' value='alterar'>
                                            <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                                            </form>
                                            <form method='post' action='operacaotopico.php'>
                                            <input type='hidden' name='id' value='$id'>
                                            <input type='hidden' name='operacao' value='excluir'>
                                            <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                                            </form>
                                            </tr>";
                                }
                            }

                            echo "</table>";
                        } else {
                            echo "Nenhuma registro encontrado.";
                        }
                    }
                    ?>

                </div>

                <div class="principal grid-80 prefix-10 suffix-10">
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Cadastro de Tópico</h3>
                    </div>

                    <form method="post" action="gravartopico.php">
                        <div class="grid-100">
                            <div class="grid-100">
                                <label>Titulo</label>
                            </div>
                            <div class="grid-100">
                                <input type="text" name="txtTitulo">

                            </div>
                            <div class="grid-100">
                                <label>Conteudo</label>
                            </div>
                            <div class="grid-100">
                                <textarea name="txtConteudo" cols="80" rows="5"></textarea>
                            </div>

                            <input type="hidden" name="txtStatus" value="Publico">

                            <div class="grid-100">
                                <label>Avaliativo</label>
                            </div>
                            <div class="grid-100">
                                <select name="txtAvaliativo" onchange="isAvaliativo(this)">
                                    <option value="1">SIM (Isso será utilizado como meio avaliativo)</option>
                                    <option value="0" selected>NÃO (Isso não será utilizado como meio avaliativo)</option>
                                </select>
                            </div>

                            <div class="isAvaliativo hidden">
                                <div class="grid-100">
                                    <label>Bimestre</label>
                                </div>
                                <div class="grid-100">
                                    <select name="txtBimestre">
                                        <option value="1">1° Bimestre (Isso irá compor a nota "A. Virtual l")</option>
                                        <option value="2">2° Bimestre (Isso irá compor a nota "A. Virtual ll")</option>
                                    </select>
                                </div>
                            </div>


                            <div class="grid-100">
                                <label>Disciplina</label>
                            </div>
                            <div class="grid-100">
                                <select name="txtDisciplina">
                                    <?php echo $listaDisciplina; ?>

                                </select>

                            </div>

                            <div class="grid-100" style="margin-top: 20px;">
                                <input type='submit' value='Gravar' class="botaoform">
                    </form>
                    <br>
                    <hr>

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