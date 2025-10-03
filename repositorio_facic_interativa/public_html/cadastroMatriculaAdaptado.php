<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/cadastro.css">

    <script>
    function listardisciplinas(idTurma) {

        $.ajax({
            type: 'POST',
            data: {
                'idTurma': idTurma,

            },
            url: 'carregardisciplinas.php',
            success: function(data) {
                document.getElementById("disciplinas").innerHTML = data;
            }
        });

    }

    function gravarDisciplina() {
        var idDisciplina = document.getElementById("txtDisciplina").value;
        var idAluno = document.getElementById("txtAluno").value;

        $.ajax({
            type: 'POST',
            data: {
                'idDisciplina': idDisciplina,
                'idAluno': idAluno,

            },
            url: 'gravarlistadisciplina.php',
            success: function(data) {
                if (data == "erro") {
                    alert("Disciplina já cadastrada para esse aluno anteriormente.")
                } else {
                    alert("Gravação concluída com sucesso.")
                }
            }
        });

    }
    </script>

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
                    <h3>Registro de Matrícula de Alunos Adaptados</h3>
                </div>

                <form method="post" action="gravarMatricula.php">
                    <div align="center">
                        <table id="cadastro">
                            <tr>
                                <td>
                                    Aluno
                                </td>
                                <td>
                                    <select name="txtAluno" id="txtAluno">
                                        <option>::Escolha um Aluno::</option>
                                        <?php
                                            include 'conexao.php';
                                            $sql = "SELECT * FROM usuario ORDER BY nome";
                                            echo $sql;
                                            $resultados = mysql_query($sql);
                                            $linhas = mysql_num_rows($resultados);
                                            if ($linhas > 0) {
                                                for ($i = 0; $i < $linhas; $i++) {
                                                    $idUsuario = mysql_result($resultados, $i, "idUsuario");
                                                    $nome = mysql_result($resultados, $i, "nome");
                                                    echo "<option value='$idUsuario'>$nome</option>";
                                                }
                                            }
                                            ?>
                                    </select>


                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Turma
                                </td>
                                <td>
                                    <select name="txtTurma" onchange="listardisciplinas(this.value)">
                                        <option>::Escolha uma turma::</option>
                                        <?php
                                            $sql = "SELECT * FROM turma WHERE semestre = '". $_SESSION['semestre']."' ORDER BY turma";
                                            echo $sql;
                                            $resultados = mysql_query($sql);
                                            $linhas = mysql_num_rows($resultados);
                                            if ($linhas > 0) {
                                                for ($i = 0; $i < $linhas; $i++) {
                                                    $idTurma = mysql_result($resultados, $i, "idTurma");
                                                    $turma = mysql_result($resultados, $i, "turma");
                                                    echo "<option value='$idTurma'>$turma</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                    <div id="disciplinas"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <td>Data da Matrícula</td>
                                <td><input type="date" name="txtData">
                                    <input type='submit' value='Gravar'>



                                </td>
                            </tr>

                        </table>
                    </div>
                </form>
                <hr>
                <center>
                    <form method="get" action="cadastroMatriculaAdaptado.php">
                        <b>Consultar Matrículas - Adaptados</b><input type="text" name="txtConsulta">
                        <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0'
                            title='Pesquisar'>
                    </form>
                </center>

                <?php
                    $consulta = "";
                    if (isset($_GET["txtConsulta"])) {
                        $consulta = $_GET["txtConsulta"];
                    
                    /*$sql = "select disciplina.semestre, disciplina.disciplina,usuario.nome,listadisciplina.idListaDisciplina,listadisciplina.ativo,listadisciplina.idAluno  
from listadisciplina, usuario, disciplina
WHERE usuario.nome LIKE '%$consulta%'
AND listadisciplina.idDisciplina = disciplina.idDisciplina
AND listadisciplina.idAluno = usuario.idUsuario 
AND semestre = '". $_SESSION['semestre']."'";*/
                      
//                       $sql = "select disciplina.semestre, usuario.ra, disciplina.disciplina,usuario.nome,listadisciplina.idListaDisciplina,listadisciplina.ativo,listadisciplina.idAluno, turma.turma
// from listadisciplina, usuario, disciplina, turma
// WHERE usuario.nome LIKE '%$consulta%'
// AND listadisciplina.idDisciplina = disciplina.idDisciplina
// AND listadisciplina.idAluno = usuario.idUsuario
// AND disciplina.idTurma = turma.idTurma 
// AND disciplina.semestre = '{$_SESSION['semestre']}'";

$sql = "SELECT disciplina.semestre, usuario.ra, disciplina.disciplina,usuario.nome,listadisciplina.idListaDisciplina,listadisciplina.ativo,listadisciplina.idAluno, turma.turma 
from listadisciplina, usuario, disciplina 
LEFT JOIN turma ON disciplina.idTurma = turma.idTurma 
WHERE usuario.nome LIKE '%$consulta%' AND listadisciplina.idDisciplina = disciplina.idDisciplina 
AND listadisciplina.idAluno = usuario.idUsuario AND disciplina.semestre = '{$_SESSION['semestre']}';";
                      
                    //echo $sql;
                    $resultados = mysql_query($sql);
                    $linhas = mysql_num_rows($resultados);
                    if ($linhas > 0) {
                        echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>RA</td>
                <td>Aluno</td>
                <td>Disciplina</td>
                <td>Turma</td>
                <td>Situação</td>
                <td colspan='2'>Operações</td>
                </tr>
";

                        for ($i = 0; $i < $linhas; $i++) {
                            $id = mysql_result($resultados, $i, "idListaDisciplina");
                            $semestre_ = mysql_result($resultados, $i, "semestre");
                            $idAluno = mysql_result($resultados, $i, "idAluno");
                            $nome = mysql_result($resultados, $i, "nome");
                            $disciplina = mysql_result($resultados, $i, "disciplina");
                            $ativo = mysql_result($resultados, $i, "ativo");
                            $ra = mysql_result($resultados, $i, "ra");
                            $turma = mysql_result($resultados, $i, "turma");
                            $situacao = "Ativo";

$turma = $turma ? $turma : "--";

                            if($ativo == "nao"){
                                $situacao = "Inativo";
                            }
                            echo "
                      
                      <tr>
                      <td>$ra</td>
                      <td>$nome</td>
                      <td>$disciplina - $semestre_</td>
                      <td>$turma</td>
                      <td>$situacao</td>
                              
                      <form method='post' action='operacaoMatricula.php'>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='idAluno' value='$idAluno'>
                      <input type='hidden' name='operacao' value='excluirdisciplina'>
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