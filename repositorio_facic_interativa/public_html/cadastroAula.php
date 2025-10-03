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
    <script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
    <link rel="stylesheet" href="css/cadastro.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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

    .select2 {
        width: 100% !important;
    }

    .select2-selection {
        background: rgba(144, 144, 144, 0.075) !important;
        border-radius: 4px !important;
        border: none !important;
        color: inherit !important;
        display: block !important;
        outline: 0 !important;
        padding: 0 1em !important;
        text-decoration: none !important;
        min-height: 44px;
    }

    .select2-search * {
        min-height: 44px;
    }

    .select2-search__field {
        min-height: 44px !important;
        padding: 10px 0 !important;
    }

    .select2-search__field::placeholder {
        color: rgb(0, 102, 153) !important;
        font-family: "Raleway", Helvetica, sans-serif !important;
        font-weight: 400 !important;
        line-height: 1.65em !important;
        font-size: 16px !important;
    }

    .select2-dropdown {
        text-align: left !important;
    }

    .select2-results__option--selected {
        display: none;
    }

    .hidden,
    #elm1_toolbar3 {
        display: none;
    }
    </style>

    <script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>

    <script>
    let date = ''

    function isAvaliativo(el) {
        const inputDate = $('input[name=txtDataAtividade]')[0]
        console.log(inputDate);
        if (el.value == 1) {
            inputDate.value = date
            $('.isAvaliativo').removeClass('hidden')
        } else {
            date = inputDate.value
            inputDate.value = ''
            $('.isAvaliativo').addClass('hidden')
        }
    }
    </script>

</head>

<body>
    <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
                //conteudo do site
                include "topo.php";
                if ($_SESSION["tipo"] == "professor") {
                    $idprofessor = $_SESSION["id"];
                } else {
                    $idprofessor = "0";
                }
                ?>


    <table width="950px" align="center" id="tabelaprincipal">



        <tr>
            <td>
                <div id="titulo">
                    <?php 
                    if($idprofessor == "0") { 
                        echo '<br><a class="botao" href="visualizarProtocolo.php">Consultar protocolo de envio de atividade</a>';
                        echo '<a class="botao" href="visualizarMaterialHistorico.php">Consultar histórico de material</a><br><br>'; 
                    } 
                    ?>

                    <br>
                    <h3>Cadastro de Aulas</h3>
                </div>
                <form method="post" action="gravarAula.php">
                    <div align="center">

                        <?php
                                    $semestre = $_SESSION["semestre"];

                                    include './conexao.php';
                                    include './Util.php';
                                    include './funcaoDisciplinas.php';
                                    $util = new Util();
                                    $seguranca    = new Seguranca();
                                    ?>

                        <table id="cadastro">
                            <tr>
                                <td>
                                    Título da Aula
                                </td>
                                <td>
                                    <input type="text" name="txtDescricao">
                                </td>
                            </tr>
                            <tr>
                                <td>Conteúdo da Aula</td>
                                <td>
                                    <textarea id="elm1" name="elm1" rows="20" cols="70" style="width: 80%"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Data da Aula
                                </td>
                                <td>
                                    <input type="datetime-local" name="txtDataAula">
                                </td>
                            </tr>
                            <tr>
                                <td>Avaliativo</td>
                                <td>
                                    <select name="txtAvaliativo" onchange="isAvaliativo(this)">
                                        <option value="1">SIM (Isso será utilizado como meio avaliativo)</option>
                                        <option value="0" selected>NÃO (Isso não será utilizado como meio avaliativo)
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="isAvaliativo hidden">
                                <td>Bimestre</td>
                                <td>
                                    <select name="txtBimestre">
                                        <option value="1">1° Bimestre (Isso irá compor a nota "A. Virtual l")</option>
                                        <option value="2">2° Bimestre (Isso irá compor a nota "A. Virtual ll")</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="isAvaliativo hidden">
                                <td>Data de Entrega da Atividade</td>
                                <td>
                                    <input type="datetime-local" name="txtDataAtividade">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Disciplina
                                </td>
                                <td>
                                    <select name="txtDisciplina[]" id="txtDisciplina">
                                        <?php
                                                    
                                        if ($_SESSION["tipo"] == "professor") {
                                            $idProfessor = $_SESSION["id"];
                                            $sql = sqlDisciplina($idProfessor, $semestre);
                                        } else {
                                            $sql = "SELECT d.*, t.turma, t.idturma FROM disciplina d,turma t where d.idTurma = t.idTurma ORDER BY d.disciplina";
                                        }

                                        $resultados = mysql_query($sql);
                                        $linhas = mysql_num_rows($resultados);
                                        if ($linhas > 0) {
                                            for ($i = 0; $i < $linhas; $i++) {

                                                $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                                                $disciplina = mysql_result($resultados, $i, "disciplina");
                                                $turma = mysql_result($resultados, $i, "turma");
                                                $idTurma = mysql_result($resultados, $i, "idturma");
                                                
                                                if ($_SESSION["tipo"] == "professor") {
                                                    $listaDisciplina .= $i==0 ? $idDisciplina : "," . $idDisciplina;
                                                }

                                                echo "<option value='$idDisciplina&$idTurma' data-turma='$idTurma'>$turma - $disciplina</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <br><br>
                                    <input type='submit' value='Gravar'>

                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
                <hr>
                <center>
                    <div class="consulta grid-50">
                        <form method="get" action="cadastroAula.php">
                            <b>Consultar Aulas</b><input type="text" name="txtConsulta">
                            <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0'
                                title='Pesquisar'>
                        </form>
                    </div>
                    <div class="consulta grid-50">
                        <form method="get" action="cadastroAula.php">
                            <b>Consultar por Disciplina</b>
                            <select name="idDisciplina">
                                <?php
                                            if ($_SESSION["tipo"] == "professor") {
                                                $idProfessor = $_SESSION["id"];
                                                //$sql = "SELECT * FROM disciplina WHERE idProfessor ='$idProfessor' and semestre = '$semestre' ORDER BY disciplina";
                                                $sql = sqlDisciplina($idProfessor, $_SESSION['semestre']);
                                            } else {
                                                $sql = "SELECT * FROM disciplina WHERE semestre = '" . $_SESSION['semestre'] . "' ORDER BY disciplina";
                                            }
                                            // echo $sql;
                                            $resultados = mysql_query($sql);
                                            $linhas = mysql_num_rows($resultados);
                                            if ($linhas > 0) {
                                                $listaDisciplina = "0";
                                              
                                                for ($i = 0; $i < $linhas; $i++) {

                                                    $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                                                    $disciplina = mysql_result($resultados, $i, "disciplina");
                                                  
                                                    $listaDisciplina .= "," . $idDisciplina;
                                                  
                                                    echo "<option value='$idDisciplina'>$disciplina</option>";
                                                }
                                            }
                                            ?>
                            </select>

                            <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0'
                                title='Pesquisar'>
                        </form>
                    </div>
                </center>

                <?php
                            $consulta = "";
                            if (isset($_GET["txtConsulta"]) || isset($_GET["idDisciplina"])) {
                                if (isset($_GET["txtConsulta"])) {
                                    $consulta = $seguranca->antisql($_GET["txtConsulta"]);
                                }

                                if ($_SESSION["tipo"] == "professor") {
                                    $sql = "SELECT turma, aula.*,date_format(dataAula,'%d/%m/%Y')as data FROM aula 
                                      LEFT JOIN turma ON turma.idTurma = aula.idTurma 
                                      WHERE descricao LIKE '%$consulta%' AND idDisciplina IN($listaDisciplina)
                                      AND semestre = '" . $_SESSION['semestre'] . "' ORDER BY idAula DESC";
                                } else {
                                    $sql = "SELECT turma, aula.*,date_format(dataAula,'%d/%m/%Y')as data FROM aula LEFT JOIN turma ON turma.idTurma = aula.idTurma WHERE descricao LIKE '%$consulta%' 
                                      AND idDisciplina IN($listaDisciplina) ORDER BY idAula DESC LIMIT 20"; //AND idDisciplina IN($listaDisciplina)
                                }
                              
                                if (isset($_GET["idDisciplina"])) {
                                    $consulta = $seguranca->antisql($_GET["idDisciplina"]);
                                    $sql = "SELECT turma, aula.*,date_format(dataAula,'%d/%m/%Y')as data FROM aula LEFT JOIN turma ON turma.idTurma = aula.idTurma WHERE iddisciplina = '$consulta' ORDER BY idAula DESC";
                                }
                                //echo $sql;
                                $resultados = mysql_query($sql);
                                $linhas = mysql_num_rows($resultados);
                                if ($linhas > 0) {
                                    echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                
                <td>Descrição</td>
                <td>Turma</td>
                <td>Avaliativo</td>
                <td>Arquivos</td>
                <td colspan='2'>Operações</td>
                </tr>
";

                                    for ($i = 0; $i < $linhas; $i++) {
                                        $id = mysql_result($resultados, $i, "idAula");
                                        $descricao = mysql_result($resultados, $i, "descricao");
                                        $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                                        $data = mysql_result($resultados, $i, "data");
                                        $dataAtividade = mysql_result($resultados, $i, "dataAtividade");
                                        $idAula = mysql_result($resultados, $i, "idAula");
                                        $turma = mysql_result($resultados, $i, "turma");

                                        $_SESSION["idDisciplina"][$i] = $idDisciplina;
                                        $_SESSION["idAula"][$i] = $idAula;
                                        $_SESSION["aula"][$i] = $descricao;
                                        $_SESSION["data"][$i] = $data;

                                        echo "
                      <form method='post' action='alterarAula.php'>
                      <tr>
                
                      <td style='width:50%;'><input type='text' value='$descricao' name='txtDescricao'></td>
                                      ";

                                        echo "
                      <td>$turma</td>
                      <td>". ($dataAtividade=="0000-00-00 00:00:00" || $dataAtividade=="2000-01-01 00:00:00" ? "Não" : "Sim") ."</td>
                      <td><a href='cadastroConteudo.php?id=$id'><img src='imagens/enviarArquivo.png'>Enviar Arquivos</a></td>
                                          <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoAula.php'>
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
            </td>
        </tr>

    </table>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    function setTurma(el) {
        var turma = el.value

        $('select[name=txtDisciplina] option').removeClass('hidden');
        $('select[name=txtDisciplina] option[data-turma!=' + turma + ']').addClass('hidden');

    }

    $(document).ready(function() {

        setTimeout(function() {
            $('#txtDisciplina').val([]).select2({
                multiple: true,
                minimumResultsForSearch: Infinity,
                placeholder: {
                    text: 'Selecione as disciplinas'
                }
            });
        }, 1000);

        tinyMCE.init({
            // General options
            mode: "textareas",
            theme: "advanced",
            plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
            // Theme options
            theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left",
            theme_advanced_statusbar_location: "bottom",
            theme_advanced_resizing: true,
            // Example content CSS (should be your site CSS)
            // content_css: "css/content.css",
            // Drop lists for link/image/media/template dialogs
            template_external_list_url: "lists/template_list.js",
            external_link_list_url: "lists/link_list.js",
            external_image_list_url: "lists/image_list.js",
            media_external_list_url: "lists/media_list.js",
            // Style formats
            style_formats: [{
                    title: 'Bold text',
                    inline: 'b'
                },
                {
                    title: 'Red text',
                    inline: 'span',
                    styles: {
                        color: '#ff0000'
                    }
                },
                {
                    title: 'Red header',
                    block: 'h1',
                    styles: {
                        color: '#ff0000'
                    }
                },
                {
                    title: 'Example 1',
                    inline: 'span',
                    classes: 'example1'
                },
                {
                    title: 'Example 2',
                    inline: 'span',
                    classes: 'example2'
                },
                {
                    title: 'Table styles'
                },
                {
                    title: 'Table row 1',
                    selector: 'tr',
                    classes: 'tablerow1'
                }
            ],
            // Replace values for the template plugin
            template_replace_values: {
                username: "Some User",
                staffid: "991234"
            }
        });
    });
    </script>

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