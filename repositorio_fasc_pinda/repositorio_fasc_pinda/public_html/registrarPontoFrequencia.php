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
                    <?php
                    include 'LoginRestrito/conexao.php';
                    $seguranca = new Seguranca();
                    $id = "";

                    if (isset($_GET["idDisciplina"])) {
                        $id = $seguranca->antisql($_GET["idDisciplina"]);
                        $_SESSION["idDisciplina"] = $id;
                    }
                    $sql = "SELECT usuario.nome,pontosfrequencia.* from pontosfrequencia,usuario WHERE pontosfrequencia.idDisciplina = '$id' and pontosfrequencia.idAluno = usuario.idUsuario ORDER BY usuario.nome";
                    echo $sql;
                    $resultados = mysqli_query($conexao, $sql);
                    $linhas = mysqli_num_rows($resultados);
                    if ($linhas > 0) {
                        //$listaAlunos[];
                        echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Aluno</td>
                <td>Frequência</td>
                <td>Menção</td>
                
                </tr>
";

                        for ($i = 0; $i < $linhas; $i++) {
                            $nome = mysql_result($resultados, $i, "nome");
                            $idFrequencia = mysql_result($resultados, $i, "idFrequencia");
                            $frequencia = mysql_result($resultados, $i, "frequencia");
                            echo "
                      
                      <tr>
                      <td>$nome</td>";

                            echo "<form method='post' action='alterarPontoFrequencia.php'>"
                            . "<td><input type='text' name='txtFrequencia' size='10' value='$frequencia'>"
                            . "<input type='hidden' name='idFrequencia' value='$idFrequencia'>"
                            . "<input type='submit' value='Gravar Frequência'></form>"
                            . "</td>";
                            $totalFrequencia = (500*$frequencia)/100;
                            $mencao = "I";

                            if ($totalFrequencia >= 450) {
                                $mencao = "MB";
                            } else if ($totalFrequencia >= 380) {
                                $mencao = "B";
                            } else if ($totalFrequencia >= 350) {
                                $mencao = "R";
                            }
                            $mencao2[$i] = $mencao;
                            echo "<td>$mencao</td>";

                            echo "</tr>";
                        }
                    } else {
                        $sql = "select idAluno from matricula where idTurma in(select idTurma from disciplina where iddisciplina = '$id')";
                        $result = mysqli_query($conexao, $sql);
                        $linhas = mysqli_num_rows($result);
                        if ($linhas > 0) {
                            for ($i = 0; $i < $linhas; $i++) {
                                $idAluno = mysql_result($result, $i, "idAluno");
                                $sqlFrequencia = "INSERT INTO pontosfrequencia VALUES(null,'$idAluno','$id','0')";
                                mysqli_query($conexao, $sqlFrequencia);
                            }
                            echo "<script>"
                            . "window.location='registrarPontoFrequencia.php?idDisciplina=$id';"
                            . "</script>";
                        } else {
                            echo "Nada encontrado.";
                        }
                    }
                    /*
                      $sql = "select iddisciplina from aula where idaula = '$id'";
                      $result = mysqli_query($conexao, $sql);
                      $linhas = mysqli_num_rows($result);
                      if ($linhas > 0) {
                      for ($i = 0;
                      $i < $linhas;
                      $i++) {
                      $idDisciplina = mysql_result($result, $i, "iddisciplina");
                      }
                      }
                      $sql = "select idaluno from matricula where idturma in(select idturma from aula where idaula = '$id')";
                      //echo $sql;
                      $result = mysqli_query($conexao, $sql);
                      $linhas = mysqli_num_rows($result);
                      $quantidadeNaoEntregue = 0;
                      if ($linhas > 0) {
                      for ($i = 0;
                      $i < $linhas;
                      $i++) {
                      $idAluno = mysql_result($result, $i, "idaluno");
                      $sqlAtividade = "select * from atividade where idaula = '$id' and idaluno = '$idAluno'";
                      $resultAtividade = mysqli_query($conexao, $sqlAtividade);
                      $linhasAtividade = mysqli_num_rows($resultAtividade);
                      if ($linhasAtividade == 0) {
                      $sql = "insert into atividade values(null,'Atividade','','$id','$idAluno','0000-00-00','','','$idDisciplina','0')";
                      //echo "<br>$sql";
                      $quantidadeNaoEntregue++;
                      mysqli_query($conexao, $sql);
                      }
                      }
                      }
                      if ($quantidadeNaoEntregue == 1) {
                      echo "<script>"
                      . "alert('$quantidadeNaoEntregue atividade não foi entregue. Este aluno foi registrado com nota 0.');"
                      . "</script>";
                      } else if ($quantidadeNaoEntregue > 1) {
                      echo "<script>"
                      . "alert('$quantidadeNaoEntregue atividades não entregue. Estes alunos foram registrados com nota 0.');"
                      . "</script>";
                      }
                     * 
                     */
                    ?>

                </table>



            </div>
        </form>
        <hr>
<div class="dados">
            <table>
                <?php
                for($i=0;$i < sizeof($mencao2);$i++){
                    echo "<tr>"
                    . "<td>$mencao2[$i]</td>"
                            . "</tr>";
                }
                ?>
            </table>
            
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