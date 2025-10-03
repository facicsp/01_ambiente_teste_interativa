<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
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

    </head>
    <body>
        <?php
        session_start();
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "administrador") {
                //conteudo do site
                include "topo.php";
                
include 'conexao.php';
 
$seguranca = new Seguranca();

include './Util.php';

$util = new Util();

$id = $seguranca->antisql($_POST["id"]);

$sql = "SELECT *,disciplina.disciplina FROM noticia,disciplina WHERE idnoticia = '$id' AND noticia.iddisciplina = disciplina.idDisciplina";

$result = mysql_query($sql);

$linhas = mysql_num_rows($result);

if ($linhas > 0) {
$tipo = mysql_result($result, 0, "tipo");
$titulo = mysql_result($result, 0, "titulo");
$texto = mysql_result($result, 0, "texto");
$data = mysql_result($result, 0, "data");
$iddisciplina = mysql_result($result, 0, "iddisciplina");
$idprofessor = mysql_result($result, 0, "idprofessor");
$status = mysql_result($result, 0, "status");
$disciplina = mysql_result($result, 0, "disciplina");
}
?><div class="principal grid-80 prefix-10 suffix-10">
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Alteração de Noticia</h3>
                    </div>

                    <form method="post" action="operacaonoticia.php"><div class="grid-100">
                            <label>Tipo</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtTipo">
                            <option value="<?php echo $tipo;?>"><?php echo $tipo;?></option><option value="Mural">Mural</option>
<option value="Aviso">Aviso</option>
</select></div><div class="grid-100">
                            <label>Titulo</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtTitulo" value="<?php echo $titulo;?>">

                        </div><div class="grid-100">
                            <label>Texto</label>
                        </div>
                        <div class="grid-100">
                            <textarea id="elm1" name="elm1" rows="20" cols="70" style="width: 80%"><?php echo $texto;?></textarea>
                        </div><div class="grid-100">
                            <label>Data</label>
                        </div>
                        <div class="grid-100">
                            <input type="date" name="txtData" value="<?php echo $data;?>" >

                        </div><div class="grid-100">
                                <label>disciplina</label>
                            </div>
                            <div class="grid-100">
                                <select name="txtIddisciplina">
                                    
                                    <option value="<?php echo $iddisciplina;?>"><?php echo $disciplina;?></option>
                                    <?php
                                    
                                    
                                    $dados = $util->carregarCombo("disciplina", "Iddisciplina", "disciplina");
                                    for($i=0;$i < sizeof($dados);$i++){
                                    echo "<option value='".$dados[$i][0]."'>".$dados[$i][1]."</option>";
                                    }
                                ?>    
                                </select>

                            </div><div class="grid-100">
                            <label>Status</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtStatus">
                            <option value="<?php echo $status;?>"><?php echo $status;?></option><option value="Ativo">Ativo</option>
<option value="Inativo">Inativo</option>
</select></div><div class="grid-100" style="margin-top: 20px;">
                            <input type="hidden" name="operacao" value="alterar">
                            <input type="hidden" name="id" value="<?php echo $id;?>">
                            <input type='submit' value='Alterar' class="botaoform">                 </form>
                    <br><hr></body>
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