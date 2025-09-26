<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
        

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
                    <div class="barratitulo"><h1>Download de Materiais</h1></div>
                    <?php
                    include "conexao.php";
                    $sql = "select idDisciplina,disciplina from disciplina where idTurma in(
select matricula.idTurma from matricula,turma
where idAluno = '$idAluno'
and matricula.idTurma = turma.idTurma 
and disciplina.semestre = '".$_SESSION['semestre']."' 
AND turma.ativo ='sim') union
select l.idDisciplina,d.disciplina from listadisciplina l,disciplina d where l.idAluno = '$idAluno' and l.idDisciplina = d.idDisciplina";
                    //echo $sql;
                    $resultados = mysql_query($sql);
                    $linhas = mysql_num_rows($resultados);
                    if ($linhas > 0) {
                        for ($i = 0; $i < $linhas; $i++) {
                            $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                            $disciplina = mysql_result($resultados, $i, "disciplina");
                            echo "<div class='grid-100' style='background-color:#ffcc00;'><h2 title='#$idDisciplina'>$disciplina</h2></div>";
                            $sqlArquivo = "select c.titulo,c.arquivo from conteudo c,aula a
where a.idDisciplina = '$idDisciplina'
and c.idAula = a.idAula";
                        $resultArquivo = mysql_query($sqlArquivo);
                        $linhasArquivo = mysql_num_rows($resultArquivo);
                        if($linhasArquivo > 0){
                            for($n=0;$n<$linhasArquivo;$n++){
                            $titulo = mysql_result($resultArquivo, $n, "titulo");
                            $arquivo = mysql_result($resultArquivo, $n, "arquivo");
                            echo "<div class='grid-50 download' style=''>"
                            . "<i class='icon fa-download' style='color:#069;'></i><a href='downloadFile.php?arquivo=$arquivo&nome=$titulo' target='_blank' class=''> $titulo</a>"
                                    . "</div>";
                            }
                        }else{
                            echo "<p>Nenhum Arquivo Encontrado.</p>";
                        }
                            
                            
                        }
                        
                    } else {
                        echo "Nenhuma registro encontrado.";
                    }
                    ?>

                </table>
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