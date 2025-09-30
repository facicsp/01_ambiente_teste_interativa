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
            if ($_SESSION["tipo"] == "aluno") {
                $idAluno = $_SESSION["id"];
                include "topo.php";
                ?>

                
        <div class="dados">
            <div class="barratitulo"><h1>Conteúdo</h1></div>
                        <?php
                        include "LoginRestrito/conexao.php";
                        $seguranca = new Seguranca();
                        $idAula = $seguranca->antisql($_GET["id"]);
                        $idAula = $_SESSION["idAula"][$idAula];
                        $_SESSION["idAulaGeral"] = $idAula;
                        if ($_SESSION["tipo"] == "aluno") {
                            $sql = "select TIMESTAMPDIFF(SECOND, dataAtividade, now()) as intervalo, aula.*,date_format(dataAula,'%d/%m/%Y ás %Hh%i')as dataAula2,date_format(dataAtividade,'%d/%m/%Y ás %Hh%i')as dataAtividade2 from aula where idAula = '$idAula' ORDER BY dataAula";
                        } else {
                            $sql = "SELECT aula.* FROM aula WHERE nome LIKE '%$consulta%' ORDER BY idAula DESC";
                        }
                        //echo $sql;
                        $resultados = mysqli_query($conexao, $sql);
                        $linhas = mysqli_num_rows($resultados);
                        if ($linhas > 0) {
                           
                            for ($i = 0; $i < $linhas; $i++) {
                                $idAula = mysql_result($resultados, $i, "idAula");
                                $descricao = mysql_result($resultados, $i, "descricao");
                                $dataAula = mysql_result($resultados, $i, "dataAula2");
                                $dataAtividade = mysql_result($resultados, $i, "dataAtividade2");
                                $conteudo =  mysql_result($resultados, $i, "conteudo");
                                $intervalo = mysql_result($resultados, $i, "intervalo");
                                //$tipo = mysql_result($resultados, $i, "tipo");
                                
                                echo "<div class='lista'>Aula: $descricao | Data da Aula: $dataAula</div>";
                                
                                if($dataAtividade == "00/00/0000 ás 00h00" || $dataAtividade == "01/01/2000 ás 00h00"){
                                 echo "<div class='atividade'>Prazo da Atividade: Não há atividade para esta aula.<img src='imagens/error.png'></div>";
                                }else if($intervalo > 0) {
                                  
                                echo "<div class='atividade'>Prazo da Atividade: $dataAtividade - O PRAZO DE ENVIO DA ATIVIDADE FOI ENCERRADO!<img src='imagens/error.png'></div>";
                                echo "<div>"
                                                
                                                    . "<h4>O prazo de envio terminou! Espero que você tenha enviado!</h4>"
                                                . "</div>";
                                }else{
                                echo "<div class='atividade'>Prazo da Atividade: $dataAtividade - <a href='cadastroAtividade.php'>ENVIAR</a></div>";
                                }
                                echo "<div style='width:100%;'>";
                                $sqlConteudo = "SELECT * FROM conteudo WHERE idAula = '$idAula'";
                                $resultadoConteudo = mysqli_query($conexao, $sqlConteudo);
                                $linhaConteudo = mysqli_num_rows($resultadoConteudo);
                                echo "<div class='barratitulo'><h1>Arquivos</h1></div>";
                                if($linhaConteudo > 0){
                                for($n=0;$n < $linhaConteudo;$n++){
                                    $titulo = mysql_result($resultadoConteudo, $n, "titulo");
                                    $arquivo = mysql_result($resultadoConteudo, $n, "arquivo");
                                    
                                    echo "<div style='width:100%;min-height:100px;'>"
                                    . "<a href='$arquivo' target='_blank'><div class='arquivo'><img src='imagens/arquivo.png'>Download: $titulo</div></a>";
                                    
                                }}else{
                                    echo "<p style='color:#000; '>Não há arquivos para esta aula.</p>";
                                }
                                echo "</div><br>";
                                
                                echo "<hr><div class='conteudo'>$conteudo</div>";
                                $sql = "SELECT d.idDisciplina,d.disciplina from disciplina d,aula a WHERE a.idAula = $idAula AND a.idDisciplina = d.idDisciplina";
                                $result = mysqli_query($conexao, $sql);
                                $idDisciplina = mysql_result($result, 0, "idDisciplina");
                                $disciplina = mysql_result($result, 0, "disciplina");
                                echo "<div style='float:left;width:5%;margin-left:5%;'>"
                                . "<form method='post' action='cadastrotopico.php'>"
                                        . "<input type='hidden' name='idDisciplina' value='$idDisciplina'>"
                                        . "<input type='hidden' name='disciplina' value='$disciplina'>"
                                        . "<input type='submit' value='Tirar Dúvida'></form></div>";
                                echo "<div style='float:left;width:5%;margin-left:5%;'>"
                                . "<form method='post' action='criarTopico.php'>"
                                        . ""
                                        . "<input type='submit' value='Questionário'></form></div>";
                                
                      echo "</div>";
                        }
                        } else {
                            echo "Nenhuma registro encontrado.";
                        }
                        ?>

        </div>
                <hr>
                <?php
                $idDisciplina2 = $seguranca->antisql($_GET["idDisciplina"]);
                echo "<div class=\"voltar\"><a href=\"exibirAula.php?idDisciplina=$idDisciplina2\"><i class=\"icon small rounded color1 fa-arrow-left\"></i> Voltar</a></div>";
                        ?>
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