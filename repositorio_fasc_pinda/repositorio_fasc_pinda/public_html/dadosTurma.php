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
    if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
        //conteudo do site
        include "topo.php";
        include './conexao.php';
        $seguranca = new Seguranca();
        $consulta  = "";
        if (isset($_POST["txtTurma"])) {
            $consulta = $seguranca->antisql($_POST["txtTurma"]);
        }
        if (!isset($_GET["idDisciplina"])) {
            
            
?>


                <div class="dados">
        <!--<table width="950px" align="center" id="tabelaprincipal">-->

                    <form method="post" action="dadosTurma.php">
                        <table>
                            <tr>
                                <td>
                                    Turma
                                </td>
                                <td>
                                    <select name="txtTurma">
                                        <option value="0">::Escolha uma turma::</option>
                                        <?php
            if ($_SESSION["tipo"] == "professor") {
                $idProfessor = $_SESSION["id"];
                $sql         = "select turma.idTurma,turma.turma from turma where idturma in(select disciplina.idTurma from disciplina where idprofessor = '$idProfessor')";
            } else {
                $sql = "SELECT * FROM turma ORDER BY turma";
            }
            //echo $sql;
            $resultados = mysql_query($sql);
            $linhas     = mysql_num_rows($resultados);
            if ($linhas > 0) {
                for ($i = 0; $i < $linhas; $i++) {
                    $idturma = mysql_result($resultados, $i, "idturma");
                    $turma   = mysql_result($resultados, $i, "turma");
                    echo "<option value='$idturma'>$turma</option>";
                }
            }
?>
                                   </select>
                                    <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <?php
            if (isset($_POST["txtTurma"])) {
?>
                       <hr>
                        <form method="post" action="gravarMensagemTurma.php">
                            <div align="center">
                                <h2>Enviar Mensagem para a Turma</h2>
                                <table id="cadastro">
                                    <tr>
                                        <td>
                                            Assunto
                                        </td>
                                        <td>
                                            <input type="text" name="txtAssunto" size="50">



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Mensagem
                                        </td>
                                        <td>
                                            <textarea name="txtMensagem" rows="5" cols="50"></textarea>
        <?php
                echo "<input type='hidden' name='idTurma' value='$consulta'>";
                
?>
                                           <input type='image' name='img_gravar' src='imagens/gravar.png' border='0' title='Gravar'>
                                            </form>
                                        </td>
                                    </tr>

                                </table>
                                <?php
            }
?>


                                <hr>
                                <h1>Alunos Matrículados</h1>
                                <hr>
                                <div style="width: 100%;margin: 0;" class="dados">
        <?php
            if ($_SESSION["tipo"] == "professor" && $consulta != "") {

                $sql = "(
                    SELECT idUsuario, nome, email FROM usuario 
                    LEFT JOIN matricula ON matricula.idAluno = usuario.idUsuario 
                    WHERE matricula.idTurma = '$consulta'
                    ORDER BY usuario.nome
                ) UNION (
                    SELECT idUsuario, nome, email FROM usuario 
                    WHERE idUsuario IN ( 
                        SELECT idAluno FROM listadisciplina 
                        LEFT JOIN disciplina ON disciplina.idDisciplina = listadisciplina.idDisciplina 
                        WHERE disciplina.idTurma = '$consulta' AND idProfessor = '$idProfessor' 
                    )
                    GROUP BY idUsuario
                )";

            } else if ($consulta != "") {
                $sql = "SELECT aula.* FROM aula WHERE nome LIKE '%$consulta%' ORDER BY idAula DESC";
            }
            //echo $sql;
            $linhas = 0;
            if ($consulta != "") {
                $resultados = mysql_query($sql);
                $linhas     = mysql_num_rows($resultados);
            }
            if ($linhas > 0) {
                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                    <td>Nome</td>
                    <td>E-mail</td>
                    <td>Mensagem</td>
                    <td>Notas</td>
                    <td>Acessos</td>
                </tr>";
                
                for ($i = 0; $i < $linhas; $i++) {
                    $email   = mysql_result($resultados, $i, "email");
                    $nome    = mysql_result($resultados, $i, "nome");
                    $idAluno = mysql_result($resultados, $i, "idUsuario");
                    echo "
                      <tr>
                        <td>$nome</td>
                        <td>$email</td>
                        <td>
                            <form method='post' action='cadastroContato.php'>
                                <input type='hidden' name='idAluno' value='$idAluno'>
                                <input type='hidden' name='nome' value='$nome'>
                                <input type='hidden' name='email' value='$email'>
                                <input type='submit' value='Enviar mensagem para este aluno'>
                            </form>
                        </td>
                        <td><a href='exibirNotas.php?idAluno=$idAluno' class='button' target='_blank'>Ver Notas</a></td>
                        <td><a href='exibirAcessos.php?idAluno=$idAluno' class='button' target='_blank'>Ver Acessos</a></td>
                      </tr>";
                }
            } else {
                echo "Nenhuma registro encontrado.";
            }
            
?>

                                    </table>



                                </div>

                                <hr>
<?php
        } else {
            if ($_SESSION["tipo"] == "professor") {
                //$sql = "select usuario.idUsuario,usuario.nome,usuario.email from usuario,matricula where matricula.idTurma = '$consulta' and matricula.idAluno = usuario.idUsuario";
                $idDisciplina = $seguranca->antisql($_GET["idDisciplina"]);
                $disciplina   = $_SESSION["disciplina"][$idDisciplina];
                $idDisciplina = $_SESSION["idDisciplina"][$idDisciplina];
                $sql          = "select usuario.idUsuario,usuario.nome,usuario.email from usuario,matricula where matricula.idTurma IN (SELECT idTurma FROM disciplina WHERE idDisciplina = '$idDisciplina') and matricula.idAluno = usuario.idUsuario ORDER BY usuario.nome";
            } else {
                $sql = "SELECT aula.* FROM aula WHERE nome LIKE '%$consulta%' ORDER BY idAula DESC";
            }
            //echo $sql;
            $resultados = mysql_query($sql);
            $linhas     = mysql_num_rows($resultados);
            if ($linhas > 0) {
                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Nome</td>
                <td>Visualizar Notas</td>
                </tr>";
                
                for ($i = 0; $i < $linhas; $i++) {
                    $nome    = mysql_result($resultados, $i, "nome");
                    $idAluno = mysql_result($resultados, $i, "idUsuario");
                    echo "
                        <tr>
                            <td title='Matriculado'>$nome</td>
                            <td><a href='exibirNotas.php?idAluno=$idAluno&idDisciplina=$idDisciplina' class='button' target='_blank'>Ver Notas</a></td>
                        </tr>";
                }
                
                // adaptados
                $sql    = "select idAluno from listadisciplina WHERE listadisciplina.idDisciplina = '$idDisciplina'";
                $result = mysql_query($sql);
                $linhas = mysql_num_rows($result);
                
                if ($linhas > 0) {
                    for ($i = 0; $i < $linhas; $i++) {
                        
                        $idUsuario     = mysql_result($result, $i, "idAluno");
                        $resultUsuario = mysql_query("select idUsuario, nome, email from usuario WHERE idUsuario = $idUsuario");
                        
                        $nome    = mysql_result($resultUsuario, 0, "nome");
                        $idAluno = mysql_result($resultUsuario, 0, "idUsuario");
                        echo "
                      
                      <tr>
                        <td title='Adaptado'>$nome</td>
                        <td><a href='exibirNotas.php?idAluno=$idAluno&idDisciplina=$idDisciplina' class='button' target='_blank'>Ver Notas</a></td>
                      </tr>";
                    }
                }
            } else {
                echo "Nenhuma registro encontrado.";
            }
            
    ?>
    </table>
</div>

<hr>
<?php
}
?>

</body>
</html>
<?php
    } else {
        echo "Acesso negado!;";
        echo "<a href='login.html'>Faça o login!</a>";
    }
} else {
    echo "<script>" . "alert('É necessário fazer o login!');" . "window.location='login.html';" . "</script>";
}
?>