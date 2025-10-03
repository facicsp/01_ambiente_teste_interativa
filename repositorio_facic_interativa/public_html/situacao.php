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
            <div class="barratitulo"><h1>Situação Acadêmica</h1></div>
                        <?php
                        include "conexao.php";
                        if ($_SESSION["tipo"] == "aluno") {
                            $sql = "select date_format(matricula.data,'%d/%m/%Y')as data,turma.turma,turma.idcurso from matricula,turma where matricula.idAluno = '$idAluno' and matricula.idTurma = turma.idTurma";
                        } else {
                            $sql = "SELECT aula.* FROM aula WHERE nome LIKE '%$consulta%' ORDER BY idAula DESC";
                        }
                        //echo $sql;
                        $resultados = mysql_query($sql);
                        $linhas = mysql_num_rows($resultados);
                        if ($linhas > 0) {
                            echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Data da Matrícula</td>
                <td>Curso</td>
                <td>Turma</td>
                
                </tr>
";

                            for ($i = 0; $i < $linhas; $i++) {
                                $data = mysql_result($resultados, $i, "data");
                                $turma = mysql_result($resultados, $i, "turma");
                                $idCurso = mysql_result($resultados, $i, "idCurso");
                                $sqlCurso = "select curso.descricao from curso where idcurso = '$idCurso'";
                                $resultadosCurso = mysql_query($sqlCurso);
                                $linhasCurso = mysql_num_rows($resultadosCurso);
                                $curso = "";
                                if($linhasCurso > 0){
                                    $curso = mysql_result($resultadosCurso, 0, "descricao");
                                }
                                
                                
                                echo "
                      <tr>
                      <td>$data</td>
                      <td>$curso</td>
                      <td>$turma</td>
                      </tr>";
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