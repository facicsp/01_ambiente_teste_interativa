<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link rel="stylesheet" href="css/cadastro.css">
        <script>
            
            function mudar(link){
                document.getElementById("video").innerHTML = "<iframe width=\"650\" height=\"400\" src=\""+link+"\" frameborder=\"0\" allow=\"accelerometer; autoplay; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
            }
            
            </script>
    </head>
    <body>
        <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "aluno") {
                $idAluno = $_SESSION["id"];
                include "topo.php";
                if(isset($_GET["link"])){
                    $link = $_GET["link"];
                }else{
                    $link = "https://www.youtube.com/embed/GXt11dieyeM";
                }
                ?>


        <div class="dados">
            <img src="imagens/kblackbox.png">
            <div class="barratitulo"><h1>AJUDA</h1></div>
            <div id="video">
                <?php
            echo "<iframe width=\"650\" height=\"400\" src=\"$link\" frameborder=\"0\" allow=\"accelerometer; autoplay; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
                    ?>
            </div>
            <p style="color:#000;">Tópicos do Vídeo:</p>
                        <ul>
                            <li><a href="https://youtu.be/GXt11dieyeM?t=72" target="_blank">1 - Dados do Aluno - Troca de Senha</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=108s" target="_blank">2 - Visualizando Aulas</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=205s" target="_blank">3 - Envio de Atividades</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=333s" target="_blank">4 - Fórum</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=386s" target="_blank">5 - Questionário</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=422s" target="_blank">6 - Minhas Notas</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=588s" target="_blank">7 - Meu Boletim</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=667s" target="_blank">8 - Minha Frequência</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=687s" target="_blank">9 - Situação Acadêmica</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=698s" target="_blank">10 - Dúvidas</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=829s" target="_blank">11 - Vídeos</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=853s" target="_blank">12 - Materiais</a></li>
                            <li><a href="https://www.youtube.com/watch?v=GXt11dieyeM&t=902s" target="_blank">13 - Bibliotecas</a></li>
                        </ul>
                        
                <hr>
  <div class="voltar"><a href="index.php"><i class="icon small rounded color1 fa-arrow-left"></i> Voltar</a></div>
                
            </div>
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