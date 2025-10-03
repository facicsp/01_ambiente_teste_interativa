<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link rel="stylesheet" href="css/cadastro.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script>
        function youtube_parser(url) {
            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
            var match = url.match(regExp);
            return (match && match[7].length==11) ? match[7] : false;
        }

        function carregarLink(e, video) {
            var link = `https://www.youtube.com/embed/${youtube_parser(video)}`;
            $(e).attr('src', link);
            $(e).removeAttr('onload');
        }
</script>
    </head>
    <body>
        <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "aluno") {
                include "topo.php";
                include './conexao.php';
                $seguranca = new Seguranca();
                
                echo "<form method='get' action='visualizarvideo.php'>"
                
                        . "<input type='text' name='txtConsulta' placeholder='Faça sua pesquisa aqui'>"
                        . "<input type='submit' value='Buscar'>"
                        . "</form>";
                
                
                echo "<div class='barratitulo'><h1>Vídeos</h1></div>";
                $idAluno = $seguranca->antisql($_SESSION["id"]);

         /*
                 $sql = "select disciplina.*, professor.nome from disciplina, professor 
where idturma in(select idturma from matricula where idaluno = '$idAluno') 
AND disciplina.idProfessor = professor.idProfessor";
            //echo $sql;
            $result = mysql_query($sql);
            $linhas = mysql_num_rows($result);
            $listaDisciplinas = "";
            if ($linhas > 0) {
                for ($i = 0; $i < $linhas; $i++) {
                    $idDisciplina = mysql_result($result, $i, "idDisciplina");
                    if($i==0){
                    $listaDisciplinas .= "$idDisciplina";
                    }else{
                        $listaDisciplinas .= ",$idDisciplina";
                    }
                }
            }
              */
              
              $listaDisciplinas = join(",", $_SESSION["idDisciplina"]);
                
                if(isset ($_GET["txtConsulta"])){
                    $consulta = $seguranca->antisql($_GET["txtConsulta"]);
                    $sql = "SELECT * FROM video WHERE titulo LIKE '%$consulta%' AND iddisciplina IN ($listaDisciplinas)";
                }
                else{
                    $sql = "SELECT * FROM video WHERE iddisciplina IN ($listaDisciplinas) ORDER BY idVideo DESC";
                }
              
                $result = mysql_query($sql);
                $linhas = mysql_num_rows($result);
                if($linhas > 0){
                    echo "<div class='grid-70 prefix-15 suffix-15'>";
                    for($i=0;$i<$linhas;$i++){
                    $titulo = mysql_result($result, $i,"titulo");
                    $video = mysql_result($result, $i,"video");
                    
                    echo "<div class=\"video\">
            
           
                    <h2>$titulo</h2>
                    <div>";
                    $video2 = split("v=", $video);
                    
                    ?>
                    <!--<video width="100%" height="300" controls>
  <source src="videos/<?php //echo $video;?>" type="video/mp4">
Your browser does not support the video tag.
</video>-->
                    
                    <iframe onload="carregarLink(this, '<?= $video ?>')" width="100%" height="250" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    
                    <?php
                    echo "</div>
                    
                    
                        
            </div>";
                    
                    
                    }
                    echo "</div>";
                }
                
                ?>


        
  <div class="voltar"><a href="index.php"><i class="icon small rounded color1 fa-arrow-left"></i> Voltar</a></div>
                
            
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