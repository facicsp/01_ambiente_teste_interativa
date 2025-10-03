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

                include 'conexao.php';

                $seguranca = new Seguranca();

                include './Util.php';

                $util = new Util();

                $id = $seguranca->antisql($_POST["id"]);

                $sql = "SELECT video.*,disciplina.disciplina FROM video,disciplina WHERE idvideo = '$id' AND video.iddisciplina = disciplina.idDisciplina";

                $result = mysql_query($sql);

                $linhas = mysql_num_rows($result);

                if ($linhas > 0) {
                    $titulo = mysql_result($result, 0, "titulo");
                    $video = mysql_result($result, 0, "video");
                    $iddisciplina = mysql_result($result, 0, "iddisciplina");
                    $disciplina = mysql_result($result, 0, "disciplina");
                    $idProfessor = $_SESSION["id"];
                }
                ?><div class="principal grid-80 prefix-10 suffix-10">
                    <div id="titulo" class="grid-100 titulo">
                        <h3>Alteração de Video</h3>
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
    </div><br>

                    <form method="post" action="operacaovideo.php" style="float: left; width: calc(50% - 10px);" >
                    <div class="grid-100">
                        <br><p>Pré-visualize seu vídeo na tela ao lado direito.</p><br>
                    </div>
                        <div class="grid-100">
                            <label>Titulo</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" name="txtTitulo" id="txtTitulo" value="<?php echo $titulo; ?>">

                        </div>
                        <div class="grid-100">
                            <label>Vídeo</label>
                        </div>
                        <div class="grid-100">
                            <input type="text" value="<?php echo $video; ?>" id="txtVideo">
                            <input type="hidden" name="txtVideo" id="video-link">

                        </div>
                        <div class="grid-100">
                            <label>disciplina</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtIddisciplina">

                                <option value="<?php echo $iddisciplina; ?>"><?php echo $disciplina; ?></option>
                                <?php
                                $sql = "SELECT * FROM disciplina WHERE idProfessor ='$idProfessor' ORDER BY disciplina";
                                //echo $sql;
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

                            <div class="grid-100" style="margin-top: 20px;">
                                <input type="hidden" name="operacao" value="alterar">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type='submit' value='Alterar' class="botaoform">                 </form>
                                <br>
                                </div>
                                </div>
                                
                                
                    <div class="video" style="float: left; width: calc(50% - 10px);">   
                        <h2 id="video-titulo">Título</h2>
                        <div>
                            <iframe 
                                id="video-src"
                                width="100%" 
                                height="250" 
                                src="" 
                                frameborder="0" 
                                allow="autoplay; encrypted-media" 
                                allowfullscreen>
                            </iframe>
                        </div> 
                    </div>
                                <hr>
                                
                                
                                </body>


                                <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script>
    function youtube_parser(url) {
        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
        var match = url.match(regExp);
        return (match && match[7].length==11) ? match[7] : false;
    }

    $('#txtTitulo').bind('change paste keyup', function() {
        $('#video-titulo').text($(this).val());
    });

    $('#txtVideo').bind('change paste keyup', function() {
        montarLink();
    });

    function montarLink() {
        var link = `https://www.youtube.com/embed/${youtube_parser($('#txtVideo').val())}`;
        $('#video-src').attr('src', link);
        $('#video-link').val(link);
    }

    (function() {
        montarLink();
        $('#video-titulo').text($('#txtTitulo').val());
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