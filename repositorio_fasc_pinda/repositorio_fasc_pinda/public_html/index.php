<?php
    session_start();

    $app = (isset($_GET["app"]) && $_GET["app"]) || isset($_SESSION["in_app"]) && $_SESSION["in_app"];
  
    if ($app) {
        $_SESSION["in_app"] = true;
        echo "<script>localStorage.setItem('app', 'true');</script>";
    }
  
  ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>FACIC INTERATIVA </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <?php include 'google-analytics.php'; ?>

        
        <style>
            .hidden { display: none; }
            .expandir { padding: 10px; color: #006699; cursor: pointer; transition: .3s; }
            .expandir:hover { text-decoration: underline; opacity: .8; }
        </style>
        
        <script>
            
            function expandir(i) {
                $('.toggle'+i).toggleClass('hidden')
            }
            
        </script>
        
    </head>
    <body>


   
        <?php

        if (isset($_SESSION["usuario"])) {
            
            //atividade do site
            include "topo.php";
            ?>

<a href="javascript: $('.banner-cpa').css('display', 'block');" style="color: #000;font-size: 1.5em;font-weight: bold;background: #ffeb3b;padding: 5px 10px;border-radius: 20px;text-decoration: none;position: fixed;bottom: 20px;right: 20px;box-shadow: 0px 0px 8px #787878;">Relatório<br>CPA
</a>

<div class="banner-cpa" style="z-index: 99; display: none; position: fixed;width: 100%;height: 100%;top: 0;left: 0;background: #020202a1;">

<div style="position: fixed;bottom: 6px;width: 100%;">
    <a href="index.php" style="padding: 5px 10px;background: #fd8609;color: #f9fdf7;border-radius: 4px;margin: 10px;">Fechar</a>
<a href="https://drive.google.com/file/d/1MQ8jguNkVCy7KxDeDMo33uI1_VWN0N0q/view" target="_blank" style="padding: 5px 10px;
    background: #fd8609;
    color: #f9fdf7;
    border-radius: 4px;
    margin: 10px;
">Ver relatório</a></div>
    
<img src="/imagens/relatorio-fasc.jpeg" style="width: 100%;height: 100%;object-fit: contain;">
    
    
</div>

                <!--<table width="950px" align="center" id="tabelaprincipal">
                    <tr>
                        <td align="center">
                            <img src="imagens/titulo.png">-->   
            <?php
            if ($_SESSION["tipo"] == "administrador") {
                $_SESSION["acesso"] = "sim";
                ?>
                <table align="center" id="menuprincipal">
                    <tr>
                        <td><a href="cadastroUsuario.php"><img src="imagens/aluno.png"><br>Cadastro de Alunos</a></td>
                        <td><a href="cadastroProfessor.php"><img src="imagens/professor.png"><br>Cadastro de Professores</a></td>

                    </tr>
                    <tr>
                        <td><a href="cadastroCurso.php"><img src="imagens/curso.png"><br>Cadastro de Cursos</a></td>                                    
                        <!--<td><a href="cadastromodulo.php"><img src="imagens/disciplina.png"><br>Cadastro de Módulos</a></td>-->
                        <td><a href="cadastroTurma.php"><img src="imagens/turma.png"><br>Cadastro de Turmas</a></td>
                    </tr>
                    <tr>

                        <td><a href="cadastroDisciplina.php"><img src="imagens/disciplina.png"><br>Cadastro de Disciplinas</a></td>
                        <td><a href="cadastroMatricula.php"><img src="imagens/reposicao.png"><br>Cadastro de Matrícula</a></td>
                    </tr>
                    <tr>
                        <td><a href="cadastroAula.php"><img src="imagens/listadisciplina.png"><br>Cadastro de Aulas</a></td>
                        <!--<td><a href="registros.php"><img src="imagens/nota.png"><br>Registros</a></td>-->
                        <td><a href="cadastromural.php"><img src="imagens/mural.png"><br>Aviso da Secretaria</a></td>
                
                    </tr>
                    <tr>
                        <td><a href="repositorio/cadastrorepositorio.php" target="_blank"><img src="imagens/kscreensaver.png"><br>Repositório</a></td>
                        <td><a href="cadastroperiodico.php"><img src="imagens/kword.png"><br>Periódicos</a></td>
                    </tr>
                </table>

            </td>
        </tr>
        </table>
        </body>
        </html>
        <?php
    } else if ($_SESSION["tipo"] == "professor") {
        $_SESSION["acesso"] = "nao";
        ?>
        <table align="center" id="menuprincipal">
            <tr>
                <td><a href="cadastroAula.php"><img src="imagens/listadisciplina.png"><br>Cadastro de Aulas</a></td>
                <td><a href="cadastroProfessor.php"><img src="imagens/professor.png"><br>Cadastro de Professores</a></td>
                <td><a href="dadosTurma.php"><img src="imagens/dadosTurma.png"><br>Dados Turmas</a></td>
                <td><a href="dadosAulas.php"><img src="imagens/dadosAulas.png"><br>Correção das Atividades</a></td>
            </tr>
            <tr>
                <td><a href="registros.php"><img src="imagens/nota.png"><br>Visualizar Notas e Frequência</a></td>
                <td><a href="notas.php"><img src="imagens/nota.png"><br>Lançar Notas</a></td>
                <td><a href="mensagens.php"><img src="imagens/chat.png"><br>Mensagens</a></td>
                <td><a href="cadastrovideo.php"><img src="imagens/video.png"><br>Cadastrar Vídeo</a></td>
            </tr>
            <tr>
                <td><a href="mural.php"><img src="imagens/mural.png"><br>Mural</a></td>
                <td><a href="visualizarmural.php"><img src="imagens/mural.png"><br>Visualizar Postagens</a></td>
                <td><a href="dadosforum.php"><img src="imagens/mensagem.png"><br>Sala Interativa</a></td>
                <td><a href="aplicarProva2.php"><img src="imagens/kblackbox.png"><br>Questionário</a></td>
            </tr>
            <tr>
                <!--<td><a href="cadastroresposta.php"><img src="imagens/lassists.png"><br>Cadastro de Respostas</a></td>-->
            </tr>
            <!--<tr>
                <td><a href="visualizarExtras.php"><img src="imagens/mensagem.png"><br>Extras</a></td>

            </tr>-->
            <tr>
                
                <!--<td><a href="aplicarProva2.php"><img src="imagens/kblackbox.png"><br>Cadastro de Provas</a></td>-->

                <td><a href="bibliotecas.php"><img src="imagens/kaddressbook.png"><br>Bibliotecas</a></td>
                <td><a href="comunicacao.php"><img src="imagens/mensagem.png"><br>Pátio do Colégio</a></td>
                <td><a href="comunicacao2.php"><img src="imagens/mensagem.png"><br>Sala de Professores</a></td>
            </tr>
        </table>

        </td>
        </tr>
        </table>
        
        </body>
        </html>

        <?php
    } else if ($_SESSION["tipo"] == "aluno") {
        $_SESSION["acesso"] = "nao";
        $semestre = $_SESSION["semestre"];
        ?>
        <script>
            //alert("Responda a pesquisa por favor. O link se encontra logo abaixo do menu.");    
        </script>
        <div class="grid-90 prefix-5 principal">
            <div class="grid-10">
                <a href="alterarUsuario.php"><i class="icon fa-lg rounded color1 fa-graduation-cap"></i><br>Cadastro de Alunos</a>
            </div>
            <div class="grid-10">
                <a href="exibirNotas.php"><i class="icon fa-lg rounded color6 fa-star-half-full"></i><br>Meus Envios</a>
            </div>
            <div class="grid-10">
                <a href="exibirBoletim.php"><i class="icon fa-lg rounded color4 fa-table"></i><br>Meu Boletim</a>
            </div>
            <div class="grid-10">
                <a href="exibirFrequencia.php"><i class="icon fa-lg rounded color11 fa-bars"></i><br>Minha Frequência</a>
            </div>
            <div class="grid-10">
                <a href="situacao.php"><i class="icon fa-lg rounded color9 fa-tag"></i><br>Situação Acadêmica</a>
            </div>
            <div class="grid-10">
                <a href="mensagens.php"><i class="icon fa-lg rounded color1 fa-sitemap"></i><br>Dúvidas</a>
            </div>
            
            <div class="grid-10">
                <a href="visualizarvideo.php"><i class="icon fa-lg rounded color5 fa-video-camera"></i><br>Vídeos</a>
            </div>
            <div class="grid-10">
                <a href="materiais.php"><i class="icon fa-lg rounded color7 fa-download"></i><br>Materiais</a>
            </div>
            <div class="grid-10">
                <a href="bibliotecas.php"><i class="icon fa-lg rounded color4 fa-book"></i><br>Bibliotecas</a>
            </div>
            <div class="grid-10">
                <a href="comunicacao.php"><i style="background-color: #f4511e;" class="icon fa-lg rounded fa-comments"></i><br>Pátio do Colégio</a>
            </div>
        </div>


        <!--
        <hr>
        <a href="https://docs.google.com/forms/d/e/1FAIpQLSckjvdL5A89P9TQvcdFbQn2dGd-CdUEcvJWDbc39iTHJjGTbw/viewform" target="_blank">
        <div class="grid-20 prefix-40 suffix-40" style="background-color: #006699;color:#FFF;margin-bottom: 10px;border-radius:15px;">
                <h2 style="color:#FFF">Pesquisa AVA</h2>
                <p>Responda a pesquisa por favor. Obrigado.</p>
                
        </div></a>-->

        <!--<div class="aviso grid-60 prefix-20 suffix-20">
            
            
            <div class="grid-80">
            <h2>Aviso</h2>
            <p>Para ganhar mais pontos, você pode enviar Atividades Extras! Saiba mais <a href="pontos.php" target="_blank">clicando aqui</a> (veja sobre Atividade Extra e Projeto).</p>
            <p>Basta entrar na Disciplina desejada abaixo e clicar na opção ATIVIDADE EXTRA.</p>
            </div>
            <div class="grid-20">
                <img src="imagens/mascote/modeloFeliz.png">
                </div>
            
        </div>-->
        <hr>
        <div class="grid-30 prefix-5">
            <div class="barratitulo"><h1>Minhas disciplinas</h1></div>
            
            <?php
            $listaDisciplinas = "";
            
            $cores[0]="#069";
            $cores[1]="#cc0033";
            $cores[2]="#009966";
            $cores[3]="#3366ff";
            $cores[4]="#990066";
            $cores[5]="#669900";
            $cores[6]="#069";
            $cores[7]="#cc0033";
            $cores[8]="#009966";
            $cores[9]="#3366ff";
            include "LoginRestrito/conexao.php";
            $seguranca = new Seguranca();
            $idAluno = $seguranca->antisql($_SESSION["id"]);

      $sql = "select disciplina.*,professor.nome from disciplina,professor 
        where idturma in(
        SELECT turma.idTurma FROM matricula 
        LEFT JOIN turma ON turma.idTurma = matricula.idTurma 
        WHERE idaluno = '$idAluno' AND ativo = 'sim'
        ) 
        AND disciplina.idProfessor = professor.idProfessor AND semestre = '$semestre'";
            //echo $sql;
            $result = mysqli_query($conexao, $sql);
            $linhas = mysqli_num_rows($result);
            
            $indice = 0;
            
            if ($linhas > 0) {
                for ($i = 0; $i < $linhas; $i++) {
                    $idDisciplina = mysql_result($result, $i, "idDisciplina");
                    $disciplina = mysql_result($result, $i, "disciplina");
                    $cargaHoraria = mysql_result($result, $i, "cargaHoraria");
                    $idProfessor = mysql_result($result, $i, "idProfessor");
                    $idTurma = mysql_result($result, $i, "idTurma");
                    $nome = mysql_result($result, $i, "nome");
                    $_SESSION["idDisciplina"][$indice] = $idDisciplina;
                    echo "<a turma='$idTurma' title='Matriculado na turma' href='exibirAula.php?idDisciplina=$indice' style='text-decoration:none;'><div class='lista' style='background-color:$cores[$i];'><p>$disciplina - <span>Docente: $nome</span></p></div></a>";
                    $indice++;
                    if($i==0){
                     $listaDisciplinas = $idDisciplina;   
                    }else{
                    $listaDisciplinas .=",$idDisciplina";
                    }
                }
                
            } else {
                echo "<p>Nenhuma disciplina encontrada.</p>";
            }
            
            
            // MATRICULA ADAPTADA
            $sql = "select disciplina.disciplina,usuario.nome,listadisciplina.idListaDisciplina,listadisciplina.ativo,professor.nome as professor,listadisciplina.idDisciplina
from listadisciplina,usuario,disciplina,professor
WHERE listadisciplina.idAluno = '$idAluno'
AND listadisciplina.idDisciplina = disciplina.idDisciplina
AND listadisciplina.idAluno = usuario.idUsuario
AND disciplina.idProfessor = professor.idProfessor AND semestre = '$semestre'";
            
            $result = mysqli_query($conexao, $sql);
            $linhas = mysqli_num_rows($result);
            if ($linhas > 0) {
                for ($i = 0; $i < $linhas; $i++) {
                    $idDisciplina = mysql_result($result, $i, "idDisciplina");
                    $idListaDisciplina = mysql_result($result, $i, "idListaDisciplina");
                    $disciplina = mysql_result($result, $i, "disciplina");
                    $nome = mysql_result($result, $i, "professor");
                    $_SESSION["idDisciplina"][$indice] = $idDisciplina;
                    echo "<a title='Matriculado na disciplina' href='exibirAula.php?idDisciplina=$indice' id='$idListaDisciplina' style='text-decoration:none;'><div class='lista' style='background-color:$cores[$i];'><p>$disciplina - <span>Docente: $nome</span></p></div></a>";
                
                    $indice++;
                    
                }
            }
            
            
            ?>
        </div>
        <div class="grid-30">
            <div class="barratitulo"><h1>Novidades e Dicas</h1></div>
            <?php
                $sqlNoticia = "select idnoticia,titulo from noticia WHERE tipo = 'Mural' AND idDisciplina IN ($listaDisciplinas) ORDER BY idnoticia DESC"; //order by rand() limit 3
            $resultNoticia = mysqli_query($conexao, $sqlNoticia);
            $linhasNoticia = mysqli_num_rows($resultNoticia);
            if ($linhasNoticia > 0) {
                for ($i = 0; $i < $linhasNoticia; $i++) {
                    $idNoticia = mysql_result($resultNoticia, $i, "idnoticia");
                    $titulo = mysql_result($resultNoticia, $i, "titulo");
                    $tipo = mysql_result($resultNoticia, $i, "tipo");
                    
                    echo "<a title='$tipo' class='". ($i >= 3 ? "toggle1 hidden" : "") ."' href=\"visualizarmural.php?id=$idNoticia\" style=\"text-decoration: none;\"><div class=\"destaque\">
        <p><i class=\"icon small rounded color1 fa-newspaper-o\"></i> $titulo</p>
        
            </div></a>";
                }
                
                if ($linhasNoticia > 3) echo "<div class='expandir' onclick='expandir(1)'>Ver todos</div>";
                
            } else {
                echo "<p>Em breve novidades e dicas.</p>";
            }
            ?>


        </div>
        <div class="grid-30 suffix-5">
            <div class="barratitulo"><h1>Avisos da Secretaria</h1></div>
            <?php
            //tipo = 'Aviso'  AND
            $sqlNoticia = "select idnoticia,titulo,tipo from noticia WHERE (tipo != 'Mural') AND (idDisciplina IN ($listaDisciplinas) OR idDisciplina = '0') ORDER BY idnoticia DESC"; //order by rand() limit 3
            //echo $sqlNoticia;
            $resultNoticia = mysqli_query($conexao, $sqlNoticia);
            $linhasNoticia = mysqli_num_rows($resultNoticia);
            if ($linhasNoticia > 0) {
                for ($i = 0; $i < $linhasNoticia; $i++) {
                    $idNoticia = mysql_result($resultNoticia, $i, "idnoticia");
                    $titulo = mysql_result($resultNoticia, $i, "titulo");
                    $tipo = mysql_result($resultNoticia, $i, "tipo");
                    echo "<a title='$tipo' class='". ($i >= 3 ? "toggle2 hidden" : "") ."' href=\"visualizarmural.php?id=$idNoticia\" style=\"text-decoration: none;\"><div class=\"destaque2\">
        <p><i class=\"icon small rounded color9 fa-eye\"></i> $titulo</p>
        
            </div></a>";
                }
                
                if ($linhasNoticia > 3) echo "<div class='expandir' onclick='expandir(2)'>Ver todos</div>";
                
            } else {
                echo "<p>Nenhum aviso encontrado.</p>";
            }
            ?>
        </div>
        <hr>
        <div class="barratitulo"><h1>Mensagens Turma</h1></div>
        <?php
        $sql = "select mensagemturma.*, date_format(data,'%d/%m/%Y')as datamensagem,professor.nome from mensagemturma, professor where idturma ='$idTurma' and mensagemturma.idprofessor = professor.idprofessor ORDER BY idMensagemTurma DESC";
        $result = mysqli_query($conexao, $sql);
        $linhas = mysqli_num_rows($result);
        if ($linhas > 0) {

            for ($i = 0; $i < $linhas; $i++) {
                $idMensagem = mysql_result($result, $i, "idMensagemTurma");
                $assunto = mysql_result($result, $i, "assunto");
                $mensagem = mysql_result($result, $i, "mensagem");
                $data = mysql_result($result, $i, "dataMensagem");
                $professor = ucwords(mysql_result($result, $i, "nome"));

                echo "<div class='lista' style='margin-top:5px;min-height:50px;'>";

                echo "<h1>$assunto</h1>"
                . "<p>Enviado por: $professor em $data</p><hr>"
                . "<p style='text-align:justify;padding:15px;'>$mensagem</p>"
                . "</div>";
            }
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
    . "window.location='login.html?app=$app';"
    . "</script>";
}
  
  echo "<script>(function() { document.title = 'FASC INTERATIVA' })()</script>";
?>