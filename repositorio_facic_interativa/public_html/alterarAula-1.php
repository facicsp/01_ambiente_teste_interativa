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
            if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor"){
                //conteudo do site
                include "topo.php";
                include "conexao.php";
                $seguranca = new Seguranca();
                $idAula = $seguranca->antisql($_POST["id"]);
                $sql = "select aula.*,disciplina.disciplina,turma.turma from aula,disciplina,turma
where idAula = '$idAula'
and aula.idDisciplina = disciplina.idDisciplina
and aula.idTurma = turma.idTurma";
                $resultados = mysql_query($sql);
                $linhas = mysql_num_rows($resultados);
                if ($linhas > 0) {

                    for ($i = 0; $i < $linhas; $i++) {
                        $descricao = mysql_result($resultados, $i, "descricao");
                        $conteudo = mysql_result($resultados, $i, "conteudo");
                        $dataAula = mysql_result($resultados, $i, "dataAula");
                        $dataAtividade = mysql_result($resultados, $i, "dataAtividade");
                        $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                        $bimestre = mysql_result($resultados, $i, "bimestre");
                        $disciplina = mysql_result($resultados, $i, "disciplina");
                        $idTurma = mysql_result($resultados, $i, "idTurma");
                        $turma = mysql_result($resultados, $i, "turma");
                        
                    }
                }
                ?>


                <table width="950px" align="center" id="tabelaprincipal">

                    <tr>
                        <td>
                            <div id="titulo">
                                <h3>ALTERAR AULAS</h3>
                            </div>
                            <form method="post" action="operacaoAula.php">
                                <?php
                                echo "<input type='hidden' name='id' value='$idAula'>";
                                echo "<input type='hidden' name='operacao' value='alterar'>";
                                ?>
                                <div align="center">
                                            <div class="grid-100">
                            <label>Tipo</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtDesafio">
                                <?php
                                    echo "<option value='".$idDesafio."'>".$desafio."</option>";
                                    include './Util.php';
                                    $util = new Util();
                                    $dados = $util->carregarCombo("desafio", "idDesafio", "tipo");
                                    for($i=0;$i < sizeof($dados);$i++){
                                    echo "<option value='".$dados[$i][0]."'>".$dados[$i][1]."</option>";
                                    }
                                ?>
                            </select>
                            
                        </div>
                                    <table id="cadastro">
                                        <tr>
                                            <td>
                                                Título da Aula
                                            </td>
                                            <td>
                                                <input type="text" name="txtDescricao" value="<?php echo $descricao; ?>">



                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Conteúdo da Aula</td>
                                            <td>
                                                <textarea id="elm1" name="elm1" rows="30" cols="70" style="width: 80%"><?php echo $conteudo; ?></textarea>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Data da Aula
                                            </td>
                                            <td>
                                                <input type="date" name="txtDataAula" value="<?php echo $dataAula; ?>">



                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Data da Atividade
                                            </td>
                                            <td>
                                                <?php
                                                if($dataAtividade == "2000-01-01" || $dataAtividade == "1998-10-19"){
                                                echo "Não há atividade.";
                                                }else{
                                                echo "<input type=\"date\" name=\"txtDataAtividade\" value=\"$dataAtividade\">";
                                                echo "<input type='checkbox' name='txtCancelarAvaliativo'>Cancelar Aula como Avaliativa";
                                                }
                                                ?>

                                            </td>
                                        </tr>
                                      
                                      <?php if ($dataAtividade != "2000-01-01" && $dataAtividade != "1998-10-19") { ?>
                                       <tr>
                                         <td>Bimestre</td>
                                         <td>
                                           <select name="txtBimestre">
                                             <option value="1" <?= $bimestre == 1 ? "selected" : "" ?> >1° Bimestre (Isso irá compor a nota "A. Virtual l")</option>
                                             <option value="2" <?= $bimestre == 2 ? "selected" : "" ?> >2° Bimestre (Isso irá compor a nota "A. Virtual ll")</option>
                                           </select>
                                         </td>
                                      </tr>
                                      <?php } ?>


                                        <tr>
                                            <td>
                                                Turma
                                            </td>
                                            <td>
                                                <select name="txtTurma">
                                                    <?php echo "<option value='$idTurma'>$turma</option>"; ?>
                                                    <?php
                                                    $sql = "SELECT * FROM turma WHERE semestre = '".$_SESSION['semestre']."' ORDER BY turma";
                                                    $resultados = mysql_query($sql);
                                                    $linhas = mysql_num_rows($resultados);
                                                    if ($linhas > 0) {
                                                        for ($i = 0; $i < $linhas; $i++) {
                                                            $idturma = mysql_result($resultados, $i, "idturma");
                                                            $semestre = mysql_result($resultados, $i, "semestre");
                                                            $turma = mysql_result($resultados, $i, "turma");
                                                            echo "<option value='$idturma'>$turma - $semestre</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Disciplina
                                            </td>
                                            <td>
                                                <select name="txtDisciplina">
                                                    <?php echo "<option value='$idDisciplina'>$disciplina</option>";
                                                    
                                                    if($_SESSION["tipo"] == "professor"){
                                            $idProfessor = $_SESSION["id"];
                                            $sql = "SELECT * FROM disciplina WHERE idProfessor ='$idProfessor' ORDER BY disciplina";
                                            }else{
                                                $sql = "SELECT * FROM disciplina ORDER BY disciplina";
                                            }echo $sql;
                                                    $resultados = mysql_query($sql);
                                                    $linhas = mysql_num_rows($resultados);
                                                    if ($linhas > 0) {
                                                        for ($i = 0; $i < $linhas; $i++) {
                                                            $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                                                            $disciplina = mysql_result($resultados, $i, "disciplina");
                                                            echo "<option value='$idDisciplina'>$disciplina</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <input type='submit' value='Alterar'>

                                            </td>
                                        </tr>


                                    </table>
                                </div>
                            </form>
                            <hr>

                            <center>
                                <a href="cadastroAula.php"><img src="imagens/voltar.png">Voltar</a>

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