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
        <script type="text/javascript">
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
                content_css: "css/content.css",
                // Drop lists for link/image/media/template dialogs
                template_external_list_url: "lists/template_list.js",
                external_link_list_url: "lists/link_list.js",
                external_image_list_url: "lists/image_list.js",
                media_external_list_url: "lists/media_list.js",
                // Style formats
                style_formats: [
                    {title: 'Bold text', inline: 'b'},
                    {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                    {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                    {title: 'Example 1', inline: 'span', classes: 'example1'},
                    {title: 'Example 2', inline: 'span', classes: 'example2'},
                    {title: 'Table styles'},
                    {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                ],
                // Replace values for the template plugin
                template_replace_values: {
                    username: "Some User",
                    staffid: "991234"
                }
            });


        </script>
        <!-- /TinyMCE -->

        <link rel="stylesheet" href="css/cadastro.css">
    </head>
    <body>
        <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
                //conteudo do site
                include "topo.php";
                ?>


                <table width="950px" align="center" id="tabelaprincipal">

                    <tr>
                        <td>
                            <div id="titulo">
                                <h3>Postagens</h3>
                            </div>
                            <form method="post" action="gravarnoticia.php">
                                <div align="center">

                                    <table id="cadastro">
                                        <!--<tr>-->
                                        <!--    <td>-->
                                        <!--        Tipo-->
                                        <!--    </td>-->
                                        <!--    <td>-->
                                                
                                        <!--        <select name="txtTipo">-->
                                        <!--            <\?php-->
                                        <!--            if($_SESSION["tipo"]=="professor"){-->
                                        <!--                echo "<option value=\"Mural\">Mural</option>";-->
                                        <!--            }else if($_SESSION["tipo"]== "administrador"){-->
                                        <!--                echo "<option value=\"Aviso\">Aviso</option>";-->
                                        <!--            }-->
                                        <!--            \?>-->
                                                    

                                        <!--        </select>-->



                                        <!--    </td>-->
                                        <!--</tr>-->


                                        <tr>
                                            <td>
                                                Título
                                            </td>
                                            <td>
                                                <input type="text" name="txtTitulo">



                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Conteúdo da Postagem</td>
                                            <td>
                                                <div class="grid-90">
                                                <textarea id="elm1" name="elm1" rows="20" cols="70" style="width: 80%"></textarea>
                                                </div>
                                                <!--<div class="grid-10">
                                                    <div style="overflow: scroll;height: 400px;">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    <img src="fotos/11_1.jpg.jpg">
                                                    
                                                    </div>-->
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Data
                                            </td>
                                            <td>
                                                <input type="date" name="txtData">



                                            </td>
                                        </tr>


                                        <tr>
                                            <td>
                                                Disciplina
                                            </td>
                                            <td>
                                                    <?php
                                                    if ($_SESSION["tipo"] == "professor") {
                                                        echo "<select name='txtDisciplina' required>";
                                                        echo "<option value=''>::Escolha uma disciplina::</option>";
                                                    } else {
                                                        echo "<select name='txtDisciplina'>";
                                                        echo "<option value='0'>::Escolha uma disciplina::</option>";
                                                    }
                                                    
                                                    
                                                    include 'conexao.php';
                                                    $seguranca = new Seguranca();
              
                                                    if ($_SESSION["tipo"] == "professor") {
    include './funcaoDisciplinas.php';
    $idProfessor = $_SESSION["id"];
    $semestre = $_SESSION["semestre"];
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
            if ($i == 0) {
                $listaDisciplina.= $idDisciplina;
            } else {
                $listaDisciplina.= "," . $idDisciplina;
            }
        }
        echo "<option value='$idDisciplina' data-turma='$idTurma'>$disciplina - $turma</option>";
    }
}
                                                    ?>
                                                </select></td></tr>
                                        <tr>
                                            <td>
                                                Status
                                            </td>
                                            <td>
                                                <select name="txtStatus">
                                                    <option value="Ativo">Ativo</option>
                                                    <option value="Inativo">Inativo</option>

                                                </select>


                                            </td>
                                        </tr>


                                    </table>
                                </div>
                                <input type="submit" value="Gravar">
                            </form>
                            <hr>
                            <center>
                                <div class="consulta grid-50">
                                    <form method="get" action="cadastromural.php">
                                        <b>Consultar Postagens</b><input type="text" name="txtConsulta">
                                        <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                                    </form></div>
                                <div class="consulta grid-50">
                                    <form method="get" action="cadastromural.php">
                                        <b>Consultar por Disciplina</b>
                                        <select name="idDisciplina">
        <?php
        if ($_SESSION["tipo"] == "professor") {
                                                        $idProfessor = $_SESSION["id"];
                                                        $sql = "SELECT * FROM disciplina WHERE idProfessor ='$idProfessor' AND semestre = '".$_SESSION['semestre']."' ORDER BY disciplina";
                                                    } else {
                                                        $sql = "SELECT * FROM disciplina WHERE semestre = '".$_SESSION['semestre']."' ORDER BY disciplina";
                                                    }
        //echo $sql;
        $resultados = mysql_query($sql);
        $linhas = mysql_num_rows($resultados);
        if ($linhas > 0) {
            for ($i = 0; $i < $linhas; $i++) {

                $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                $disciplina = mysql_result($resultados, $i, "disciplina");
                if ($_SESSION["tipo"] == "professor") {

                    if ($i == 0) {
                        $listaDisciplina.= $idDisciplina;
                    } else {
                        $listaDisciplina.= "," . $idDisciplina;
                    }
                }
                echo "<option value='$idDisciplina'>$disciplina</option>";
            }
        }
        ?>
                                        </select>

                                        <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                                    </form></div>
                            </center>

        <?php
        $consulta = "";
        if(isset($_GET["txtConsulta"])||isset($_GET["idDisciplina"])){
        if (isset($_GET["txtConsulta"])) {
            $consulta = $seguranca->antisql($_GET["txtConsulta"]);
        }

        if ($_SESSION["tipo"] == "professor") {
            $idProfessor = $_SESSION["id"];
            $sql = "SELECT idnoticia,titulo FROM noticia WHERE titulo LIKE '%$consulta%' AND idprofessor = '$idProfessor' ORDER BY titulo DESC";
            //echo $sql;
        } else {
            $sql = "SELECT idnoticia,titulo FROM noticia WHERE titulo LIKE '%$consulta%' ORDER BY titulo DESC";
        }
        if (isset($_GET["idDisciplina"])) {
            $consulta = $seguranca->antisql($_GET["idDisciplina"]);
            $sql = "SELECT idnoticia,titulo FROM noticia WHERE iddisciplina = '$consulta' ORDER BY titulo DESC";
        }
        //echo $sql;
        $resultados = mysql_query($sql);
        $linhas = mysql_num_rows($resultados);
        if ($linhas > 0) {
            echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Descrição</td>
                <td colspan='3'>Operações</td>
                </tr>
";

            for ($i = 0; $i < $linhas; $i++) {
                $id = mysql_result($resultados, $i, "idnoticia");
                $titulo = mysql_result($resultados, $i, "titulo");
                
                echo "
                      <form method='post' action='alterarnoticia.php'>
                      <tr>
                      <td>$id</td>
                      <td><input type='text' value='$titulo' name='txtDescricao'></td>
                       ";

                echo "

                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaonoticia.php'>
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