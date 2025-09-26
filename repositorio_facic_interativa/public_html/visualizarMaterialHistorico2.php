<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- TinyMCE -->
    <script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="tinymce/default.js"></script>
    <!-- /TinyMCE -->

    <link rel="stylesheet" href="css/cadastro.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
    function carregardisciplina(idturma, idprofessor) {

        $.ajax({
            type: 'POST',
            data: {
                'idTurma': idturma,
                'idProfessor': idprofessor,
            },
            url: 'carregardisciplina.php',
            success: function(data) {
                $('select[name=idDisciplina]').html(data);
            }
        });
    }

    $(document).ready(function() {
        $('select[name=idTurma]').change(function(e) {
            carregardisciplina(e.target.value);
        });
    });
    </script>

    <style>
    .hidden {
        display: none;
    }

    a.botao:hover {
        text-decoration: none;
        opacity: .8;
        transition: .3s;
    }

    a.botao {
        border: none !important;
        border-radius: 5px;
        margin-right: 10px;
    }

    .selecionado {
        background-color: #65e56a !important;
    }
    </style>

    <script>
    function turma(turma) {
        $('#titulo .botao').removeClass('selecionado');
        $('.botao#' + turma).addClass('selecionado');

        if (turma == 'todas') {
            $('select[name="idTurma"] option').removeClass('hidden');
            return;
        }

        $('select[name="idTurma"] option').each(function(i, e) {
            var text = $(this).text();
            if (text.toLowerCase().indexOf(turma) != -1)
                $(this).removeClass('hidden');
            else
                $(this).addClass('hidden');
        });
    }
    </script>

    <style>
    .hidden,
    #elm1_toolbar3 {
        display: none;
    }

    a.botao:hover {
        text-decoration: none;
        opacity: .8;
        transition: .3s;
    }

    a.botao {
        border: none !important;
        border-radius: 5px;
        margin-right: 10px;
    }
    </style>

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
                    <br>
                    <a href="javascript:turma('todas')" id="todas" class="botao">Todas</a>
                    <a href="javascript:turma('rh')" id="rh" class="botao">RH</a>
                    <a href="javascript:turma('adm')" id="adm" class="botao">ADM</a>
                    <a href="javascript:turma('cc')" id="cc" class="botao">CC</a>
                    <a href="javascript:turma('ef')" id="ef" class="botao">EF</a>
                    <a href="javascript:turma('dd')" id="dd" class="botao">DD</a>
                    <a href="javascript:turma('mat')" id="mat" class="botao">MAT</a>
                    <a href="javascript:turma('ped')" id="ped" class="botao">PED</a>
                    <a href="javascript:turma('pós')" id="pós" class="botao">PÓS</a>

                    <br><br><br>
                    <h3>Consultar histórico de material</h3>
                </div>
                <div align="center">
                    <?php
                    $semestre = $_SESSION["semestre"];
                    include './conexao.php';
                    include './Util.php';
                    include './funcaoDisciplinas.php';
                    $util = new Util();
                    ?>
                </div>

                <hr>

                <center>
                    <div class="consulta grid-50">
                        <form method="get" action="#">
                            <b>Buscar turma</b>
                            <select name="idTurma" required>
                                <option selected disabled value="">::Escolha uma turma::</option>
                                <?php
                                            $sql = "SELECT idTurma, semestre, turma FROM turma ORDER BY semestre DESC, turma ASC";

                                                $resultados = mysql_query($sql);
                                                $linhas = mysql_num_rows($resultados);
                                                if ($linhas > 0) {
                                                    for ($i = 0; $i < $linhas; $i++) {
                                                        $idturma = mysql_result($resultados, $i, "idturma");
                                                        $semestre = mysql_result($resultados, $i, "semestre");
                                                        $turma = mysql_result($resultados, $i, "turma");
                                                        echo "<option value='$idturma'>$semestre :: $turma</option>";
                                                    }
                                                }
                                            ?>
                            </select>

                            <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0'
                                title='Pesquisar'>
                        </form>
                    </div>

                    <div class="consulta grid-50">
                        <form method="get" action="#">
                            <b>Buscar disciplina</b>
                            <select name="idDisciplina" required>
                                <option selected disabled value="">::Primeiro selecione uma turma::</option>
                            </select>

                            <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0'
                                title='Pesquisar'>
                        </form>
                    </div>
                    <br><br><br><br>
                </center>
                <br><br><br><br><br><br>



                <?php
                
                    if (isset($_GET["idTurma"]) || isset($_GET["idDisciplina"])) {
                        $seguranca = new Seguranca();

                        if (isset($_GET["idTurma"])) {
                            $idTurma = $seguranca->antisql($_GET["idTurma"]);
                            $sql = "SELECT * FROM aula 
                                    LEFT JOIN conteudo ON conteudo.idAula = aula.idAula 
                                    LEFT JOIN disciplina ON disciplina.idDisciplina = aula.iddisciplina 
                                    WHERE aula.idTurma = $idTurma ORDER BY disciplina";
                        } else {
                            $idDisciplina = $seguranca->antisql($_GET["idDisciplina"]);
                            $sql = "SELECT * FROM aula 
                                    LEFT JOIN conteudo ON conteudo.idAula = aula.idAula 
                                    LEFT JOIN disciplina ON disciplina.idDisciplina = aula.iddisciplina 
                                    WHERE aula.iddisciplina = $idDisciplina ORDER BY disciplina";
                        }

                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);

                            if ($linhas > 0) {
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                                    <tr>
                                    <td>Disciplina</td>
                                    <td>Título do Material</td>
                                    <td>Arquivo</td>
                                    </tr>";

                                $aux = "";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idConteudo");
                                    $titulo = mysql_result($resultados, $i, "titulo");
                                    $arquivo = mysql_result($resultados, $i, "arquivo");
                                    $disciplina = mysql_result($resultados, $i, "disciplina");

                                    echo "<tr>
                                    <td>$disciplina</td>
                                    <td>$titulo</td>
                                    <td><a href='downloadFile.php?arquivo=$arquivo&nome=$titulo' target='_blank'><img src='imagens/acessoArquivo.png'>Acesse o arquivo</a></td>
                                    <form method='post' action='operacaoConteudo.php'>
                                    <input type='hidden' name='id' value='$id'>
                                    <input type='hidden' name='operacao' value='excluir'>
                                    </form>
                                    </tr>";
                                }

                                echo "</table>";
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
    echo "<script>"
    . "alert('É necessário fazer o login!');"
    . "window.location='login.html';"
    . "</script>";
}
?>