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
  if($_SESSION["tipo"] == "administrador"){
      //conteudo do site
      include "topo.php";
?>
    
        

        <table width="950px" align="center" id="tabelaprincipal">

            <tr>
                <td>
                    <div id="titulo">
                        <h3>Copiar Matrícula da Turma</h3>
                    </div>
          
                    <form method="post" action="gravarCopiarTurma.php">
                        <div align="center">
                            <table id="cadastro">
                                
                                <tr>
                                    <td>
                                        Turma Antiga
                                    </td>
                                    <td>
                                        <select name="txtTurma">
                                            <option>::Escolha uma turma::</option>
                                            <?php
                                            include 'LoginRestrito/conexao.php';
                                            $sql = "SELECT * FROM turma ORDER BY turma";
                                            echo $sql;
                                            $resultados = mysqli_query($conexao, $sql);
                                            $linhas = mysqli_num_rows($resultados);
                                            if ($linhas > 0) {
                                                for ($i = 0; $i < $linhas; $i++) {
                                                    $idTurma = mysql_result($resultados, $i, "idTurma");
                                                    $turma = mysql_result($resultados, $i, "turma");
                                                    echo "<option value='$idTurma'>$turma</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                </tr>
                                <tr>
                                    <td>
                                        Turma Nova
                                    </td>
                                    <td>
                                        <select name="txtTurmaNova">
                                            <option>::Escolha uma turma::</option>
                                            <?php
                                            if ($linhas > 0) {
                                                for ($i = 0; $i < $linhas; $i++) {
                                                    $idTurma = mysql_result($resultados, $i, "idTurma");
                                                    $turma = mysql_result($resultados, $i, "turma");
                                                    echo "<option value='$idTurma'>$turma</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                </tr>
                                <tr>
                                    <td>Data da Matrícula</td>
                                    <td><input type="date" name="txtData">
                                        <input type='image' name='img_gravar' src='imagens/gravar.png' border='0' title='Gravar'>

                                    </td>
                                </tr>

                            </table>
                        </div>
                    </form>
                    <hr>
                    <center>
                        <form method="get" action="cadastroMatricula.php">
                            <b>Consultar Matrículas</b><input type="text" name="txtConsulta">
                            <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                        </form>
                    </center>

                    <?php
                    $consulta = "";
                    if (isset($_GET["txtConsulta"])) {
                        $consulta = $_GET["txtConsulta"];
                    
                    $sql = "select matricula.*,matricula.data as dataMatricula,usuario.nome,turma.turma from matricula,turma,usuario where matricula.idAluno = usuario.idUsuario and matricula.idTurma = turma.idTurma and nome like '%$consulta%'";
                    //echo $sql;
                    $resultados = mysqli_query($conexao, $sql);
                    $linhas = mysqli_num_rows($resultados);
                    if ($linhas > 0) {
                        echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Aluno</td>
                <td>Turma</td>
                <td>Data</td>
                <td colspan='2'>Operações</td>
                </tr>
";

                        for ($i = 0; $i < $linhas; $i++) {
                            $id = mysql_result($resultados, $i, "idMatricula");
                            $idAluno = mysql_result($resultados, $i, "idAluno");
                            $nome = mysql_result($resultados, $i, "nome");
                            $idTurma = mysql_result($resultados, $i, "idTurma");
                            $turma = mysql_result($resultados, $i, "turma");
                            $data = mysql_result($resultados, $i, "dataMatricula");
                            echo "
                      <form method='post' action='operacaoMatricula.php'>
                      <tr>
                      <td>";
                            ?>
                            <select name="txtAluno">

                                <?php
                                $sql2 = "SELECT idUsuario,nome FROM usuario ORDER BY nome";
                                //echo $sql;
                                $resultados2 = mysqli_query($conexao, $sql2);
                                $linhas2 = mysqli_num_rows($resultados2);
                                echo "<option value='$idAluno'>$nome</option>";
                                for ($n = 0; $n < $linhas2; $n++) {
                                    $idAluno2 = mysql_result($resultados2, $n, "idUsuario");
                                    $aluno2 = mysql_result($resultados2, $n, "nome");
                                    if ($idAluno != $idAluno2) {
                                        echo "<option value='$idAluno2'>$aluno2</option>";
                                    }
                                }
                                ?>
                            </select>
                                <?php
                                echo "</td>
                      <td>";
                                ?>
                            <select name="txtTurma">

        <?php
        $sql2 = "SELECT * FROM turma ORDER BY turma";
        //echo $sql;
        $resultados2 = mysqli_query($conexao, $sql2);
        $linhas2 = mysqli_num_rows($resultados2);
        echo "<option value='$idTurma'>$turma</option>";
        for ($n = 0; $n < $linhas2; $n++) {
            $idTurma2 = mysql_result($resultados2, $n, "idTurma");
            $turma2 = mysql_result($resultados2, $n, "turma");
            if ($idTurma != $idTurma2) {
                echo "<option value='$idTurma2'>$turma2</option>";
            }
        }
        ?>
                            </select>
                                <?php
                                echo "</td>
                      <td>
                      <input type='date' name='txtData' value='$data'>
                      </td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoMatricula.php'>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='excluir'>
                      <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                      </form>
                      </tr>
";
                            }

                            echo "</table>";
                        } else {
                            echo "Nenhuma registro encontrado.";
                        }
                        }
                        ?>
                </td>
            </tr>

        </table>

    </body>
</html>
<?php
  }else{
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