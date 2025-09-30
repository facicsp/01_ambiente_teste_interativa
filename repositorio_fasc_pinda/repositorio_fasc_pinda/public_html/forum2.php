<?php
session_start();
function MontarLink ($texto)
    {
           if (!is_string ($texto))
               return $texto;
 
        $er = "/((http|https|ftp|ftps):\/\/(www\.|.*?\/)?|www\.)([a-zA-Z0-9]+|_|-)+(\.(([0-9a-zA-Z]|-|_|\/|\?|=|&)+))+/i";
        preg_match_all ($er, $texto, $match);
 
        foreach ($match[0] as $link)
        {
            //coloca o 'http://' caso o link não o possua
            if(stristr($link, "http://") === false && stristr($link, "https://") === false)
            {
                $link_completo = "http://" . $link;
            }else{
                $link_completo = $link;
            }
             
            $link_len = strlen ($link);
 
            //troca "&" por "&", tornando o link válido pela W3C
           $web_link = str_replace ("&", "&amp;", $link_completo);
           $texto = str_ireplace ($link, "<a href=\"" . $web_link . "\" target=\"_blank\">". (($link_len > 60) ? substr ($web_link, 0, 25). "...". substr ($web_link, -15) : $web_link) ."</a>", $texto);
            
        }
        return $texto;
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
        

        <link rel="stylesheet" href="css/cadastro.css">
        <style>
            .grupocomentario{
                min-height:100px;

                margin-bottom:5px;
            }
            .grupocomentario div{
                float:left;
                
            }
            .grupocomentario img{
                width:20%;
            }
            .caixa{
                
                display:none;
                position:fixed;
                margin-left:25%;
                margin-right: 25%;
                margin-top:100px;
                padding:10px;
                width:50%;
                min-height: 200px;
                border:3px solid #d0d0d0;
                background-color: #FFFFFF;
                
                
}
.date{
    color: #1a8c43 !important;
    background-color: transparent  !important;
    font-size: 1em  !important;
    font-weight: 600  !important;
}
.conteudos{
        background-color: #eaeaea;
    border-left: 1px solid #D3D3D3;
}
.conteudoforum{
    padding: 10px;
    text-align: justify;
        background-color: #d8d8d8;
}
.conteudoforum span{
        color: #000;
        background-color: #d8d8d8;
}

.comentario{
        /*background-color: #e0e0e0;*/
}
.comentario p, .subresposta {
        color: #000;
    padding: 10px 10px 10px 0;
    text-align: justify;
}

.subresposta{
        background-color: #FFF;
        padding: 10px;
    
}

.comment-name {
    color: #383b43 !important;
    padding: 10px;
}

.conteudos a{
    color: blue;
}
.expandir{
    color: #b7b7b7;
    cursor: pointer;
}
.oculto{
    display: none;
    font-weight: normal;
    color: #000;
}
        </style>
        <script>
            function expandir(el, id) {
                document.getElementById(id).style.display = "contents";
                el.style.display = "none";
            }
            function darnota(idAluno,idDisciplina,idTopico,nota){
                document.getElementById("caixa").style.display = "block";
                document.getElementById("caixa").innerHTML = "<p style=\"text-align:center;\"><a href=''>[Fechar]</a></p><p id='mensagemnota'></p><label>Nota</label><input type=\"text\" id=\"txtNota\" name=\"txtNota\" value='"+nota+"' style=\"width:10%;margin-left: 45%;\"><input type=\"button\" value=\"Gravar Nota\" onclick=\"gravarNota("+idAluno+","+idDisciplina+","+idTopico+")\">";
                //alert(idAluno+"-"+idDisciplina+"-"+idTopico+"-")
            }
            
            
function gravarNota(idAluno,idDisciplina,idTopico){
    
    var nota = document.getElementById("txtNota").value;
    
    $.ajax({
        type: 'POST',
        data: {
            'idAluno': idAluno,
            'idDisciplina':idDisciplina,
            'idTopico':idTopico,
            'nota':nota
        },
        url : 'gravarNotaForum.php',
        success: function(data) {
            document.getElementById("mensagemnota").innerHTML = data;
        }
    });
}
    
    
            function comentar(idTopico) {
                document.getElementById("comentar").innerHTML = "<form method='post' action='gravarcomentario.php'><div style='width:70%;float:left;'><label>Comentário</label><textarea name='txtComentario' cols='30' rows='4' width='50%'></textarea></div><div style='width:30%;float:left;'><input type='hidden' name='topico' value='" + idTopico + "'><input type='submit' value='Enviar Comentário'></form></div>";
            }

            function abrirrespostas(i) {

                if (document.getElementById("divrespostas" + i).style.display === "block") {
                    document.getElementById("divrespostas" + i).style.display = "none";
                    document.getElementById("verresposta" + i).value = "Ver respostas";
                } else if (document.getElementById("divrespostas" + i).style.display === "none") {
                    document.getElementById("divrespostas" + i).style.display = "block";
                    document.getElementById("verresposta" + i).value = "-";
                }
            }
            function sub(i, idComentario, idTopico) {
                document.getElementById("sub" + i).innerHTML = "<form method='post' action='gravarsub.php'><input type='hidden' name='idComentario' value='" + idComentario + "'><input type='hidden' name='topico' value='" + idTopico + "'><label>Responder:</label><textarea name='txtSubResposta'></textarea><input type='submit' value='Gravar Resposta'></form>";
                document.getElementById("sub" + i).style.display = "block";
            }
        </script>


    </head>
    <body>
        
        <?php
        function getNome($tabela,$campo,$id){
            //mysql_set_charset("UTF8");
            $query = "SELECT nome FROM $tabela WHERE $campo = '$id'";
            //echo $query;
            $resultNome = mysqli_query($conexao, $query);
            if(mysqli_num_rows($resultNome) > 0){
                $nome = mysql_result($resultNome, 0, "nome");
                
                return $nome;
            }else{
                return "Usuário não encontrado.";
            }
        }
        
        function abreviar($resposta, $id, $exibe=300) {
            $len = strlen($resposta);
            
            if ($len > $exibe) {
                $html = substr($resposta, 0, $exibe) . "<b class='expandir' title='Clique para expandir o comentário' onclick='expandir(this, \"$id\")'> Veja mais...</b>";
                $html .= "<b id='$id' class='oculto'>".substr($resposta, $exibe, $len)."</b>";
                return $html;
            }
            
            return $resposta;
        }
        
        include "topo.php";
        ?>
        <div class="caixa" id="caixa">
            
        </div>
        <div class='topicos'>


            <?php
            if (isset($_SESSION["usuario"])) {
                if ($_SESSION["tipo"] == "aluno" || $_SESSION["tipo"] == "professor") {
                    //conteudo do site
                    if ($_SESSION["tipo"] == "professor") {
                        echo "<a href='dadosforum.php' style='color:#069;'>&lt;&lt;Voltar</a>
            <hr>";
                    } else if ($_SESSION["tipo"] == "aluno") {
                        echo "<a href='index.php' style='color:#069;'>&lt;&lt;Voltar</a>
            <hr>";
                    }
                    echo "<h2>:::Tópicos:::</h2>";

                    include 'LoginRestrito/conexao.php';
                    $seguranca = new Seguranca();
                    if (isset($_POST["idDisciplina"])) {
                        $idDisciplina = $seguranca->antisql($_POST["idDisciplina"]);
                        $disciplina = $seguranca->antisql($_POST["disciplina"]);
                        $_SESSION["disciplina"] = $idDisciplina;
                    } else {
                        $idDisciplina = $seguranca->antisql($_SESSION["disciplina"]);
                    }

                    $sql = "SELECT * FROM topico WHERE idDisciplina = '$idDisciplina'";
                    $result = mysqli_query($conexao, $sql);
                    $linhas = mysqli_num_rows($result);
                    if ($linhas > 0) {
                        for ($i = 0; $i < $linhas; $i++) {
                            $idTopico = mysql_result($result, $i, "idTopico");
                            $titulo = mysql_result($result, $i, "titulo");
                            echo "<a href='forum.php?idTopico=$idTopico' style='text-decoration:none;'>
                <div class='titulotopico'><p>$titulo</p></div></a>";
                        }
                    } else {
                        echo "<p>Nenhum tópico foi criado para essa disciplina.</p>";
                    }
                    ?>
                </div>
                <div class='conteudos'>

                    <?php
                    if (isset($_GET["idTopico"])) {
                        $idTopico = $seguranca->antisql($_GET["idTopico"]);
                        $sql = "SELECT * FROM topico WHERE idTopico = '$idTopico' AND idDisciplina = '$idDisciplina'";
                        //echo $sql;
                        $result = mysqli_query($conexao, $sql);
                        $linhas = mysqli_num_rows($result);
                        if ($linhas > 0) {
                            
                            $resultBimestre = mysqli_query($conexao, "SELECT bimestre FROM forumavaliacao WHERE idTopico = '$idTopico'");
                            $avaliativo = mysqli_num_rows($resultBimestre);
                            
                            for ($i = 0; $i < $linhas; $i++) {

                                $titulo = mysql_result($result, $i, "titulo");
                                $conteudo = mysql_result($result, $i, "conteudo");
                                
                                echo "<h1>$titulo</h1>";
                                echo "<div class='conteudoforum'><span>$conteudo</span></div>"
                                . "<hr>";
                                echo "<div style='width:100%;'><input type='button' value='Comentar Tópico' onclick='comentar(\"$idTopico\")'></div>";
                                echo "<div id='comentar' class='comentar'></div>"
                                . "<hr>";
                            }
                        

                        //Localizar comentários
                        $sql = "SELECT * FROM comentario WHERE idTopico = '$idTopico' ORDER BY idComentario";
                        //echo $sql;
                        $result = mysqli_query($conexao, $sql);
                        $linhas = mysqli_num_rows($result);
                        if ($linhas > 0) {
                            for ($i = 0; $i < $linhas; $i++) {

                                $idComentario = mysql_result($result, $i, "idComentario");
                                $comentario = MontarLink(mysql_result($result, $i, "comentario"));
                                $idUsuario = mysql_result($result, $i, "idUsuario");
                                $tipo = mysql_result($result, $i, "tipo");
                                $data = date_format(date_create(mysql_result($result, $i, "data")),"d/m/y H:i");
                                $imagem = "";
                                $nome = "";
                                
                                if($tipo == "aluno"  && $_SESSION["tipo"] == "professor"){
                                    $nome = getNome("usuario", "idUsuario", $idUsuario);
                                    $imagem = "aluno.png";
                                    
                                    $resultNota = mysqli_query($conexao, "select * from notaforum where idAluno = '$idUsuario' AND idDisciplina = '$idDisciplina' AND idTopico = '$idTopico'");
                                    if (mysqli_num_rows($resultNota) > 0) $nota = mysql_result($resultNota, 0, "nota");
                                    else $nota = 0;
                                    
                                    echo "<div class='grupocomentario'>
                                            <div style='width:20%;text-align: right;'>
                                                <img src='imagens/$imagem'>
                                                <p style='color: #000'>$nome 
                                                    <span class='date'>$data ";
                                                    
                                                if ($avaliativo) {
                                                    echo "<br><b style='color: blue'>nota: $nota</b></span> <img onclick='darnota($idUsuario,$idDisciplina,$idTopico,$nota)' src='imagens/notaPequeno.png' style='width:24px;height:24px;' title='Gravar Nota'>";
                                                } else {
                                                    echo "</span>";
                                                }
                                            
                                            echo "</p></div>";
                                    
                                }else if($tipo == "aluno"){
                                    $nome = getNome("usuario", "idUsuario", $idUsuario);
                                    $imagem = "aluno.png";
                                    echo "<div class='grupocomentario'>"
                                . "<div style='width:20%;text-align: right;'><img src='imagens/$imagem'><p class='comment-name'>$nome <span class='date'>$data</span></p></div>";
                                }
                                else if($tipo == "professor") {
                                    $nome = getNome("professor", "idProfessor", $idUsuario);
                                    $imagem = "professor.png";
                                    echo "<div class='grupocomentario'>"
                                . "<div style='width:20%;text-align: right;'><img src='imagens/$imagem'><p class='comment-name'>$nome <span class='date'>$data</span></p></div>";
                                }
                                
                                echo "<input type='hidden' name='idTopico' value='$idTopico'>";
                                echo "<div id='comentario' class='comentario'>"
                                . "<p>".abreviar($comentario, $idComentario)."</p>"
                                . "</div>"
                                . "<div class='botaoresponder'><input type='button' value='Responder' onclick='sub($i,$idComentario,$idTopico)'></div></div>";
                                echo "<div class='sub' id='sub$i' style='display:none;'></div>";
                                // echo "<input type='button' value='Ver respostas' id='verresposta$i' onclick='abrirrespostas($i)' class='botaomais'>"
                                echo "<div style='display:block;' id='divrespostas$i' class='divrespostas'>";
                                
                                $sqlSub = "SELECT * FROM subresposta WHERE idComentario = '$idComentario' ORDER BY idSub";
                                //echo $sqlSub;
                                $resultSub = mysqli_query($conexao, $sqlSub);
                                $linhasSub = mysqli_num_rows($resultSub);
                                if ($linhasSub > 0) {
                                    for ($j = 0; $j < $linhasSub; $j++) {
                                        $idUsuario = mysql_result($resultSub, $j, "idUsuario");
                                        $resposta = MontarLink(mysql_result($resultSub, $j, "resposta"));
                                        $tipo = mysql_result($resultSub, $j, "tipo");
                                        
                                        if (trim($resposta) != "") {
                                            $data_sub = date_format(date_create(mysql_result($resultSub, $j, "data")),"d/m/y H:i");
                                            $nome = "";
                                            
                                            if($tipo == "aluno"){
                                                $nome = getNome("usuario", "idUsuario", $idUsuario);
                                                $imagem = "aluno.png";
                                            
                                            }else if($tipo == "professor"){
                                                $nome = getNome("professor", "idProfessor", $idUsuario);
                                                $imagem = "professor.png";
                                            }
                                            echo "<div><div style='float:left;width:40%;text-align: right;'><img src='imagens/$imagem'><p class='comment-name' alt='$idUsuario'>$nome <span class='date'>$data_sub</span></p></div>";
                                            echo "<div class='subresposta' style='".($_SESSION['id'] == $idUsuario || $imagem == "professor.png" ? 'background-color:#d8d8d8;' : '')."' id='subresposta$j'>"
                                            .abreviar($resposta, "$i-$j", 200)."</div></div>";
                                        }
                                    }
                                }
                                echo "</div><hr> ";
                            }
                        }
                    }else{
                        echo "<p>Você está tentando acessar um tópico não correspondente à sua disciplina.</p>";
                    }
                        //Fim do Localizar comentários
                    }
                    ?>
                </div>

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