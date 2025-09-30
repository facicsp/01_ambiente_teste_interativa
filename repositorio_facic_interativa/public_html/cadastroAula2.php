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

  <style>
  .hidden,
  #elm1_toolbar3 {
    display: none;
  }
  </style>

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
          <h3>Cadastro de Aulas</h3>
        </div>
        <form method="post" action="gravarAula.php">
          <div align="center">

            <?php
                                    include 'LoginRestrito/conexao.php';
                                    include './Util.php';
                                    $util = new Util();
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
                  <input type="date" name="txtDataAula">
                </td>
              </tr>


              <tr>
                <td>Avaliativo</td>
                <td>
                  <select name="txtAvaliativo" onchange="isAvaliativo(this)">
                    <option value="1">SIM (Isso será utilizado como meio avaliativo)</option>
                    <option value="0" selected>NÃO (Isso não será utilizado como meio avaliativo)</option>
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
                  <input type="date" name="txtDataAtividade">
                </td>
              </tr>


              <tr>
                <td>
                  Turma
                </td>
                <td>
                  <select name="txtTurma">
                    <option>::Escolha uma turma::</option>
                    <?php
                                                    //        include 'LoginRestrito/conexao.php';
                                                    $seguranca = new Seguranca();
                                                    if ($_SESSION["tipo"] == "professor") {
                                                        $idProfessor = $_SESSION["id"];
                                                        $sql = "select turma.idturma,turma.turma from turma where idturma in(select disciplina.idTurma from disciplina where idprofessor = '$idProfessor')";
                                                    } else {
                                                        $sql = "SELECT * FROM turma WHERE semestre = '".$_SESSION['semestre']."' ORDER BY turma";
                                                    }
                                                    //echo $sql;
                                                    $resultados = mysqli_query($conexao, $sql);
                                                    $linhas = mysqli_num_rows($resultados);
                                                    if ($linhas > 0) {
                                                        for ($i = 0; $i < $linhas; $i++) {
                                                            $idturma = mysql_result($resultados, $i, "idturma");
                                                            $turma = mysql_result($resultados, $i, "turma");
                                                            echo "<option value='$idturma'>$turma</option>";
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
                    <option>::Escolha uma disciplina::</option>
                    <?php
                                                    if ($_SESSION["tipo"] == "professor") {
                                                        $idProfessor = $_SESSION["id"];
                                                        $sql = "SELECT d.*,t.turma FROM disciplina d,turma t where d.idTurma = t.idTurma and d.idProfessor = '$idProfessor' ORDER BY d.disciplina";
                                                    } else {
                                                        $sql = "SELECT d.*,t.turma FROM disciplina d,turma t where d.idTurma = t.idTurma ORDER BY d.disciplina";
                                                    }
                                                    echo $sql;
                                                    $resultados = mysqli_query($conexao, $sql);
                                                    $linhas = mysqli_num_rows($resultados);
                                                    if ($linhas > 0) {
                                                        for ($i = 0; $i < $linhas; $i++) {

                                                            $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                                                            $disciplina = mysql_result($resultados, $i, "disciplina");
                                                            $turma = mysql_result($resultados, $i, "turma");
                                                            if ($_SESSION["tipo"] == "professor") {

                                                                if ($i == 0) {
                                                                    $listaDisciplina.= $idDisciplina;
                                                                } else {
                                                                    $listaDisciplina.= "," . $idDisciplina;
                                                                }
                                                            }
                                                            echo "<option value='$idDisciplina'>$disciplina - $turma</option>";
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
          <div class="consulta grid-50">
            <form method="get" action="cadastroAula.php">
              <b>Consultar Aulas</b><input type="text" name="txtConsulta">
              <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
            </form>
          </div>
          <div class="consulta grid-50">
            <form method="get" action="cadastroAula.php">
              <b>Consultar por Disciplina</b>
              <select name="idDisciplina">
                <?php
                                            if ($_SESSION["tipo"] == "professor") {
                                                $idProfessor = $_SESSION["id"];
                                                $sql = "SELECT * FROM disciplina WHERE idProfessor ='$idProfessor' ORDER BY disciplina";
                                            } else {
                                                $sql = "SELECT * FROM disciplina WHERE semestre = '".$_SESSION['semestre']."' ORDER BY disciplina";
                                            }
                                            echo $sql;
                                            $resultados = mysqli_query($conexao, $sql);
                                            $linhas = mysqli_num_rows($resultados);
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
            </form>
          </div>
        </center>

        <?php
                            $consulta = "";
                            if (isset($_GET["txtConsulta"])) {
                                $consulta = $seguranca->antisql($_GET["txtConsulta"]);
                            }
                            
                            

                            if ($_SESSION["tipo"] == "professor") {
                                $sql = "SELECT aula.*,date_format(dataAula,'%d/%m/%Y')as data FROM aula WHERE descricao LIKE '%$consulta%' AND idDisciplina IN($listaDisciplina) ORDER BY idAula DESC";
                                //echo $sql;
                            } else {
                                $sql = "SELECT aula.*,date_format(dataAula,'%d/%m/%Y')as data FROM aula WHERE descricao LIKE '%$consulta%' 
                                     ORDER BY idAula DESC LIMIT 20"; //AND idDisciplina IN($listaDisciplina)
                            }
                            if (isset($_GET["idDisciplina"])) {
                                $consulta = $seguranca->antisql($_GET["idDisciplina"]);
                                $sql = "SELECT aula.*,date_format(dataAula,'%d/%m/%Y')as data FROM aula WHERE iddisciplina = '$consulta' ORDER BY idAula DESC";
                            }
                            //echo $sql;
                            $resultados = mysqli_query($conexao, $sql);
                            $linhas = mysqli_num_rows($resultados);
                            if ($linhas > 0) {
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                
                <td>Descrição</td>
                <td colspan='2'>Operações</td>
                <td>Arquivos</td>
                <td>Frequência</td>
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idAula");
                                    $descricao = mysql_result($resultados, $i, "descricao");
                                    $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                                    $data = mysql_result($resultados, $i, "data");
                                    $idAula = mysql_result($resultados, $i, "idAula");

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

                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoAula.php'>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='excluir'>
                      <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                      </form>
                      <td><a href='cadastroConteudo.php?id=$id'><img src='imagens/enviarArquivo.png'>Enviar Arquivos</a></td>
                      <td><a href='registrarFrequencia.php?id=$i'><img src='imagens/frequenciaPequeno.png'>Registrar Frequência</a></td>
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