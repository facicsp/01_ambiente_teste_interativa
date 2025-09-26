<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
        

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
            if ($_SESSION["tipo"] == "aluno") {
                $idAluno = $_SESSION["id"];
                include "topo.php";
                ?>
        <div class="botoes">
        <a href="forum.php"><div>Sala Interativa</div></a>
        <a href="responder.php"><div>Questionário</div></a>
        </div>

        <div class="dados grid-90 prefix-5 suffix-5">
            
                        <?php
                        include "conexao.php";
                        $seguranca = new Seguranca();
                        if ($_SESSION["tipo"] == "aluno") {
                            $idDisciplina = $seguranca->antisql($_GET["idDisciplina"]);
                            
                            $idDisciplina2 = $idDisciplina;
                            if($_SESSION["idDisciplina"][$idDisciplina]){
                            $idDisciplina = $_SESSION["idDisciplina"][$idDisciplina];
                            $_SESSION["disciplina"] = $seguranca->antisql($idDisciplina);
                            
                            //echo "<a href='forum.php'><div class=\"barratitulo\"><i class=\"icon small rounded color9 fa-sitemap\"></i><h1>Fórum</h1></div></a>";
                            //echo "<a href='responder.php'><div class=\"barratitulo\"><i class=\"icon small rounded color9 fa-sitemap\"></i><h1>Questionário</h1></div></a>";
            echo "<div class=\"barratitulo\"><i class=\"icon small rounded color1 fa-book\"></i><h1>Minhas Aulas</h1></div>";
                            //$sql = "select aula.*,desafio.tipo,date_format(dataAula,'%d/%m/%Y')as dataAula2,date_format(dataAtividade,'%d/%m/%Y')as dataAtividade2 from aula,desafio where aula.iddisciplina = '$idDisciplina' AND aula.idDesafio = desafio.idDesafio ORDER BY dataAula DESC";
                            //echo $sql;
                            ?>
            <!--<a href="cadastroExtra.php" style="text-decoration: none;"><div class="botaoextra"><i class="icon small rounded color6 fa-cogs"></i><h2>Atividades Extras</h2></div></a>-->
                            <div class="aulas grid-45 suffix-5">
                            <div class='barratitulo'><h1>Módulos</h1></div>
                            <?php
                            $sql = "select aula.*,date_format(dataAula,'%d/%m/%Y ás %Hh%i')as dataAula2,date_format(dataAtividade,'%d/%m/%Y ás %Hh%i')as dataAtividade2 from aula where iddisciplina = '$idDisciplina' AND TIMESTAMPDIFF(SECOND, dataAula, now()) > 0 ORDER BY dataAula";
                        
                        //echo $sql;
                        $resultados = mysql_query($sql);
                        $linhas = mysql_num_rows($resultados);
                        if ($linhas > 0) {
                            

                            for ($i = 0; $i < $linhas; $i++) {
                                $idAula = mysql_result($resultados, $i, "idAula");
                                $descricao = mysql_result($resultados, $i, "descricao");
                                $dataAula = mysql_result($resultados, $i, "dataAula2");
                                $dataAtividade = mysql_result($resultados, $i, "dataAtividade2");
                                //$idDesafio = mysql_result($resultados, $i, "idDesafio");
                                //$tipo = mysql_result($resultados, $i, "tipo");
                                $_SESSION["idAula"][$i] = $idAula;
                                if($dataAtividade == "01/01/2000 ás 00h00" || $dataAtividade == "19/10/1998 ás 00h00"){
                                    $dataAtividade = "Não há atividade.";
                                }
                                
                                if($dataAula == "00/00/0000 ás 00h00"){
                                    $dataAula = "Não informada.";
                                }
                      /*if($idDesafio == 3){
                          echo "<div class='aula2'>
                    <img src='imagens/riddler.png'>
                    <p><span>Atividade:</span> $descricao</p>
                    <p><span>Data da Aula:</span> $dataAula</p>
                    <p><span>Data de Entrega da Atividade:</span> $dataAtividade</p>
                    <a href='exibirConteudo.php?id=$i' class='button special'>Visualizar Conteúdo da Aula</a>
                </div>";
                      }
                       * else if($tipo == "Aula"){
                      
                          echo "<div class='aula'>
                    <p><span>Aula:</span> $descricao</p>
                    <p><span>Data da Aula:</span> $dataAula</p>
                    <a href='exibirConteudo.php?id=$i' class='button special'>Visualizar Conteúdo da Aula</a>
                </div>";
                      }
                      
                          else{ */
                      echo "<div class='aula grid-100'>
                    <p><span>Módulo:</span> $descricao</p>
                    <p><span>Data da Aula:</span> $dataAula</p>
                    <p><span>Data de Entrega da Atividade:</span> $dataAtividade</p>
                    <a href='exibirConteudo.php?id=$i&idDisciplina=$idDisciplina2' class='button special'>Visualizar Conteúdo da Aula</a>
                </div>";
                        //}
                            }
                            } else {
                    echo "<p style='color: #000'>Nenhuma registro encontrado.</p>";
                            }}else{
                                echo "Dados não encontrados.";
                            }
                        ?>
                </div>
                <div class="videos grid-45 prefix-5">
                    <?php
                    
                    echo "<div class='barratitulo'><h1>Vídeos</h1></div>";
                $sql = "SELECT * FROM video WHERE idDisciplina = $idDisciplina ORDER BY idvideo DESC";
                          
                          
                          //echo "<script>console.log('$idDisciplina');</script>";
                
                $result = mysql_query($sql);
                $linhas = mysql_num_rows($result);
                
                if($linhas > 0) {
                    echo "<div class='grid-100'>";
                    
                    for($i=0;$i<$linhas;$i++) {
                        $titulo = mysql_result($result, $i,"titulo");
                        $video = mysql_result($result, $i,"video");
                        echo "<div class=\"video grid-100\"><h2>$titulo</h2><div>";
                        $video2 = split("v=", $video);
                        $video2 = split("&", $video2[1]);
                        
                        // echo '<iframe width="100%" height="250" src="https://www.youtube.com/embed/'.$video2[0].'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div></div>';
                   
                        ?>
                            <iframe onload="" width="100%" height="250" src="<?= $video ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    
                        <?php
                   
                    }
                    
                    echo "</div>";
                } else {
                    echo "<p style='color: #000'>Nenhuma registro encontrado.</p>";
                }
                    
                    ?>
                </div>
                <!--</table>-->
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
        } }else {
    echo "<script>"
    . "alert('É necessário fazer o login!');"
    . "window.location='login.html';"
    . "</script>";
        }
?>