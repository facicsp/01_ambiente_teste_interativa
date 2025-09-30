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
            if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
                //conteudo do site
                include "topo.php";
                ?>


        <div class="dados">
<!--<table width="950px" align="center" id="tabelaprincipal">-->
                    <form method="post" action="cadastrotopico.php">
                        <table>
                        <tr>
                            <td>
                                Disciplina:
                            </td>
                            <td>Novo Tópico</td>
                            <td>Consultar Tópico</td>
                            <td>Visualizar Discussão</td>
                        </tr>
                            
                                    <?php
                                    include 'LoginRestrito/conexao.php';
                                    $seguranca = new Seguranca();
                                    if($_SESSION["tipo"] == "professor"){
                                            $idProfessor = $_SESSION["id"];
                                            // $sql = "SELECT d.*,t.turma FROM disciplina d,turma t WHERE idProfessor ='$idProfessor' AND t.semestre = '". $_SESSION['semestre'] ."' AND d.idTurma = t.idTurma ORDER BY disciplina";
                                            //$sql = "SELECT disciplina.*, turma.turma FROM disciplina
                                            //        INNER JOIN turma ON turma.idTurma = disciplina.idTurma 
                                            //        WHERE idProfessor ='$idProfessor' 
                                            //        AND disciplina.semestre = '". $_SESSION['semestre'] ."'
                                            //        ORDER BY disciplina";
                                      include "funcaoDisciplinas.php";
                                $sql = sqlDisciplina($idProfessor, $_SESSION['semestre']);
                                            }else{
                                                $sql = "SELECT * FROM disciplina ORDER BY disciplina";
                                            }
                                    //echo $sql;
                                    $resultados = mysqli_query($conexao, $sql);
                                    $linhas = mysqli_num_rows($resultados);
                                    if ($linhas > 0) {
                                        for ($i = 0; $i < $linhas; $i++) {
                                            $iddisciplina = mysql_result($resultados, $i, "iddisciplina");
                                            $disciplina = mysql_result($resultados, $i, "disciplina");
                                            $turma = mysql_result($resultados, $i, "turma");
                                            echo "<tr>"
                                            . "<td>$disciplina - $turma</td>"
                                            . "<td style='text-align:center;'>"
                                                    . "<form method='post' action='cadastrotopico.php'>"
                                                    . "<input type='hidden' name='idDisciplina' value='$iddisciplina'>"
                                                    . "<input type='hidden' name='disciplina' value='$disciplina'>"
                                                    . "<input type='submit' value='Novo Tópico'>"
                                                    . "</form>"
                                                    . "</td>"
                                            . "<td  style='text-align:center;'>"
                                                    . "<form method='get' action='cadastrotopico.php'>"
                                                    . "<input type='hidden' name='idDisciplina' value='$iddisciplina'>"
                                                    . "<input type='hidden' name='txtConsulta' value=''>"
                                                    . "<input type='submit' value='Consultar Tópicos'>"
                                                    . "</form>"
                                                    . "</td>"
                                            . "<td  style='text-align:center;'>"
                                                    . "<form method='post' action='forum.php'>"
                                                    . "<input type='hidden' name='idDisciplina' value='$iddisciplina'>"
                                                    . "<input type='hidden' name='disciplina' value='$disciplina'>"
                                                    . "<input type='submit' value='Visualizar'>"
                                                    . "</form>"
                                                    . "</td></tr>";        
                                        }
                                    }
                                    ?>
                                </select>
                                <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>

                            </td>
                        </tr>
                </table>
                        <hr>
        <div class="dados">
            
                        
                </table>
                


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