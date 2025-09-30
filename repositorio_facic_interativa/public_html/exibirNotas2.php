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
            if ($_SESSION["tipo"] == "aluno" || $_SESSION["tipo"] == "professor") {
                if($_SESSION["tipo"] == "aluno"){
                $idAluno = $_SESSION["id"];
                }else if($_SESSION["tipo"] == "professor"){
                    $idAluno = $_GET["idAluno"];
                }
                include "topo.php";
                ?>


                <div class="dados">
                    <div class="barratitulo"><h1>Minhas Notas</h1></div>


                    <?php
                    include "LoginRestrito/conexao.php";
                    $seguranca = new Seguranca();
                    if($_SESSION["tipo"] == "professor"){
                    
                    $idDisciplina = $_GET["idDisciplina"];
                    
                    $sqlDisciplina = "select iddisciplina,disciplina from disciplina 
                        where idDisciplina = '$idDisciplina' and idturma in(select idturma from matricula where idaluno = '$idAluno') 
                        union
                        select l.idDisciplina,d.disciplina from listadisciplina l,disciplina d where l.idAluno = '$idAluno' and l.idDisciplina = d.idDisciplina AND l.idDisciplina = '$idDisciplina'";
                    
                    }else{
                    $sqlDisciplina = "select iddisciplina,disciplina from disciplina where disciplina.semestre ='".$_SESSION['semestre']."' AND idturma in(select idturma from matricula where idaluno = '$idAluno') union
select l.idDisciplina,d.disciplina from listadisciplina l,disciplina d where l.idAluno = '$idAluno' and l.idDisciplina = d.idDisciplina AND d.semestre ='".$_SESSION['semestre']."'";
                }
                    //echo $sqlDisciplina;
                     $resultDisciplina = mysqli_query($conexao, $sqlDisciplina);
                    $linhasDisciplina = mysqli_num_rows($resultDisciplina);
                    
                    
                    if ($linhasDisciplina > 0) {
                        for ($i = 0; $i < $linhasDisciplina; $i++) {
                            $soma = 0;
                            $porcentagem = 0;
                            $idDisciplina = mysql_result($resultDisciplina, $i, "iddisciplina");
                            $disciplina = mysql_result($resultDisciplina, $i, "disciplina");
                            echo "<table border='1' align='center'>"
                            . "<tr>"
                            . "<td colspan='4' style='background-color:#FFD324;color:#069;text-align:center;'>$disciplina</td></tr>"
                            . "<tr>"
                            . "<td>Aula</td>"
                            . "<td>Data da Atividade</td>"
                            . "<td>Nota</td>"
                                    . "<td>Correção</td></tr>";
                            $sql2 = "select aula.descricao,date_format(atividade.data,'%d/%m/%Y')as dataAula,atividade.nota,atividade.retorno from atividade,aula where atividade.idaluno = '$idAluno' and atividade.iddisciplina = '$idDisciplina' and atividade.idaula = aula.idaula";
                            $result2 = mysqli_query($conexao, $sql2);
                            $linhas2 = mysqli_num_rows($result2);
                            if ($linhas2 > 0) {
                                
                                for ($n = 0; $n < $linhas2; $n++) {
                                    $aula = mysql_result($result2, $n, "descricao");
                                    $data = mysql_result($result2, $n, "dataAula");
                                    $nota = mysql_result($result2, $n, "nota");
                                    $retorno = mysql_result($result2, $n, "retorno");
                                    
                                    if($data == "00/00/0000"){
                                        $data = "Não entregue.";
                                    }
                                    $soma +=$nota;
                                    echo "<tr>"
                                    . "<td>$aula</td>"
                                    . "<td>$data</td>"
                                    . "<td>$nota</td>"
                                            . "<td>$retorno</td>"
                                            . "</tr>";
                                }
                                // $media = $soma/$linhas2;
                                $total = number_format($soma, 2, ",", " ");
                                echo "<tr>"
                                . "<td colspan='2'>Total:</td>"
                                . "<td>$total</td></tr>"
                                . "</table><hr>";
                            }
                        }
                    } else {
                        echo "Nenhuma registro encontrado.";
                    }
                    ?>

                </table>
                <hr>
                <div class="voltar"><a href="index.php"><img src="imagens/voltar.png">Voltar</a></div>

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