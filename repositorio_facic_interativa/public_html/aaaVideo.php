<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link rel="stylesheet" href="css/cadastro.css">
        
    </head>
    <body>
        <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "aluno") {
                include "topo.php";
                include 'LoginRestrito/conexao.php';
                $seguranca = new Seguranca();
                
                
                echo "<div class='barratitulo'><h1>Vídeos</h1></div>";
                $idDisciplina = 27;
                $sql = "SELECT * FROM video WHERE titulo LIKE '%$consulta%' AND idDisciplina = $idDisciplina ORDER BY idvideo DESC";
                
                $result = mysqli_query($conexao, $sql);
                $linhas = mysqli_num_rows($result);
                
                if($linhas > 0) {
                    echo "<div class='grid-70 prefix-15 suffix-15'>";
                    
                    for($i=0;$i<$linhas;$i++) {
                        $titulo = mysql_result($result, $i,"titulo");
                        $video = mysql_result($result, $i,"video");
                        echo "<div class=\"video\"><h2>$titulo</h2><div>";
                        $video2 = split("v=", $video);
                        
                        echo '<iframe width="100%" height="250" src="https://www.youtube.com/embed/'.$video2[1].'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div></div>';
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