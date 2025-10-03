<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/cadastro.css">
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
    ?>
            <div class="principal grid-80 prefix-10 suffix-10">
                <div id="titulo" class="grid-100 titulo">
                    <h3>Cadastro de Video</h3>
                </div>
              
              <div style="
    text-align: left;
    background-color: #ffeb3b5c;
    padding: 10px;
    border-radius: 5px;
"><p style="
    font-size: 1.1em;
    font-weight: bold;
">Para obter o endereço URL de um vídeo do YouTube:</p><ol><li>Em Youtube.com, encontre o vídeo em questão.</li><li>Clique em <strong>Compartilhar </strong>sob o vídeo.</li><li>Copie o link.</li></ol>
    </div>

                <form method="post" action="salvarvideo.php" style="float: left; width: calc(50% - 10px);" enctype="multipart/form-data">
                    <div class="grid-100">
                        <br>
                        <p>Pré-visualize seu vídeo na tela ao lado direito.</p><br>
                    </div>
                    <div class="grid-100">
                        <label>Titulo</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" name="txtTitulo" id="txtTitulo" placeholder="Título">

                    </div>
                    <div class="grid-100">
                        <label>Vídeo</label>
                    </div>
                    <div class="grid-100">
                        <!--<input type="file" name="arquivo">-->
                        <input type="text" id="txtVideo" placeholder="Endereço URL do vídeo Youtube">
                        <input type="hidden" name="txtVideo" id="video-link">

                    </div>
                    <div class="grid-100">
                        <label>disciplina</label>
                    </div>
                    <div class="grid-100">
                        <select name="txtDisciplina">
                            <option>::Escolha uma disciplina::</option>
                            <?php
                            //include 'conexao.php';
                            //$seguranca = new Seguranca();
                            if ($_SESSION["tipo"] == "professor") {
                                $idProfessor = $_SESSION["id"];
                                //$sql = "SELECT idDisciplina, disciplina, turma FROM disciplina 
                                //  LEFT JOIN turma ON turma.idTurma = disciplina.idTurma 
                                //  WHERE idProfessor ='$idProfessor' AND disciplina.semestre = '".$_SESSION['semestre']."' ORDER BY turma, disciplina";
                                include "funcaoDisciplinas.php";
                                $sql = sqlDisciplina($idProfessor, $_SESSION['semestre']);
                            } else {
                                $sql = "SELECT idDisciplina, disciplina, turma FROM disciplina 
                                                          LEFT JOIN turma ON turma.idTurma = disciplina.idTurma 
                                                          WHERE disciplina.semestre = '" . $_SESSION['semestre'] . "' ORDER BY turma, disciplina";
                            }

                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);
                            $listaDisciplina = "0";

                            if ($linhas > 0) {
                                for ($i = 0; $i < $linhas; $i++) {

                                    $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                                    $disciplina   = mysql_result($resultados, $i, "disciplina");
                                    $turma        = mysql_result($resultados, $i, "turma");

                                    $listaDisciplina .= "," . $idDisciplina;

                                    echo "<option value='$idDisciplina'>$turma - $disciplina</option>";
                                }
                            }
                            ?>
                        </select>

                    </div>
                    <div class="grid-100" style="margin-top: 20px;">
                        <input type='submit' value='Gravar' class="botaoform">
                </form>
            </div>

            <br>

            <div class="video" style="float: left; width: calc(50% - 10px);">
                <h2 id="video-titulo">Título</h2>
                <div>
                    <iframe id="video-src" width="100%" height="250" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                    </iframe>
                </div>
            </div>


            <br>
            <hr>
            <div class="grid-100">
                <label>Consultar video</label>
            </div>
            <div class="grid-100">
                <form method="get" action="cadastrovideo.php">
                    <input type="text" name="txtConsulta">

                    </select>
                    <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                </form>
            </div>
            <div class="grid-100" style="margin-top:30px;">
                <?php

                $consulta = "";
                if (isset($_GET["txtConsulta"])) {
                    $consulta = $_GET["txtConsulta"];
                    //, disciplina
                    //AND video.iddisciplina = disciplina.idDisciplina 
                  
$sql = "SELECT video.idvideo, video.titulo, disciplina.disciplina, turma.turma FROM video, disciplina, turma 
  WHERE video.titulo LIKE '%$consulta%' 
  AND video.iddisciplina = disciplina.idDisciplina
  AND disciplina.idDisciplina IN ($listaDisciplina) 
  AND disciplina.idTurma = turma.idTurma 
  AND disciplina.semestre = '".$_SESSION['semestre']."'";
                  
                  //echo $sql;

                    $resultados = mysql_query($sql);
                    $linhas = mysql_num_rows($resultados);
                    if ($linhas > 0) {

                        echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Descrição</td>
                <td>Turma - Disciplina</td>
                <td colspan='2'>Operações</td>
                
                </tr>
";

                        for ($i = 0; $i < $linhas; $i++) {
                            $id = mysql_result($resultados, $i, "idvideo");
                            $descricao = ereg_replace("'", "", mysql_result($resultados, $i, "titulo"));
                            $disciplina = mysql_result($resultados, $i, "disciplina");
                            $turma = mysql_result($resultados, $i, "turma");
                          
                            echo "
                      <form method='post' action='alterarvideo.php'>
                      <tr>
                      <td>$id</td>
                      <td><input type='text' value='$descricao' name='txtDescricao'></td>
                      <td><input type='text' value='$turma - $disciplina' name='txtDisciplina' readonly></td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaovideo.php'>
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

            </div>
            </div>

</body>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script>
    (function() {
        function youtube_parser(url) {
            //var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
            //var match = url.match(regExp);
            //return (match && match[7].length == 11) ? match[7] : false;
          var rx = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;

          return url.match(rx)[1]
        }

        $('#txtTitulo').bind('change paste keyup', function() {
            $('#video-titulo').text($(this).val());
        });

        $('#txtVideo').bind('change paste keyup', function() {
            var link = `https://www.youtube.com/embed/${youtube_parser($(this).val())}`;
            $('#video-src').attr('src', link);
            $('#video-link').val(link);
        });
    })()
</script>


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