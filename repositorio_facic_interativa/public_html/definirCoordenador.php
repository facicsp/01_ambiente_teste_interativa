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
            if ($_SESSION["tipo"] == "administrador") {
                //conteudo do site
                include "conexao.php";
                include "topo.php";
                $seguranca = new Seguranca();
                
                if (isset($_POST['action']) && $_POST['action'] == "enviado") {
                    $idProfessor = $seguranca->antisql($_POST['txtProfessor']);
                    $idTurma = $seguranca->antisql($_POST['txtTurma']);
                    mysql_query("INSERT INTO coordenador VALUES (NULL, '$idProfessor', '$idTurma')");
                    echo "<script>alert('Salvo com sucesso!');</script>";
                }

                if (isset($_POST['operacao']) && $_POST['operacao'] == "excluir") {
                    $idCoordenador = $seguranca->antisql($_POST['id']);
                    mysql_query("DELETE FROM coordenador WHERE idCoordenador = '$idCoordenador'");
                    echo "<script>alert('Excluído com sucesso!');</script>";
                }
                ?>

                <table width="950px" align="center" id="tabelaprincipal">

                    <tr>
                        <td>
                            <div id="titulo">
                                <h3>Adicionar Turma</h3>
                            </div>
                            <?php
                            if($_SESSION["tipo"] != "professor"){
                                
                            ?>
                            <form method="post" action="#">
                                <div align="center">
                                    <table id="cadastro">
                                        <tr>
                                            <td>
                                                Coordenador
                                            </td>
                                            <td>
                                                <select name="txtProfessor" required>
                                                    <option value="">Selecione um Professor</option>
                                                    <?php
                                                        $result = mysql_query("SELECT idProfessor, nome FROM professor");
                                                        for ($i=0; $i < mysql_num_rows($result); $i++) {
                                                            $idProfessor = mysql_result($result, $i, "idProfessor");
                                                            $nome = mysql_result($result, $i, "nome");
                                                            echo "<option value='$idProfessor'>$nome</option>";
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
                                                <select name="txtTurma" required>
                                                    <option value="">Selecione uma turma</option>
                                                    <?php
                                                        $result = mysql_query("SELECT idTurma, turma FROM turma WHERE semestre = '".$_SESSION['semestre']."'");
                                                        for ($i=0; $i < mysql_num_rows($result); $i++) {
                                                            $idTurma = mysql_result($result, $i, "idTurma");
                                                            $turma = mysql_result($result, $i, "turma");
                                                            echo "<option value='$idTurma'>$turma</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            </td>
                                            <td>
                                                <input type='hidden' value='enviado' name="action">
                                                <input type='submit' value='Gravar'>

                                            </td>
                                        </tr>




                                    </table>
                                </div>
                            </form>
                            <hr>

                            <center>
                                <h1>Turmas coordenadas</h1>
                            </center>
                            <?php
            }
                            
                            $resultados = mysql_query("SELECT idCoordenador, turma, nome FROM coordenador 
                                                        LEFT JOIN professor ON professor.idProfessor = coordenador.idProfessor 
                                                        LEFT JOIN turma ON turma.idTurma = coordenador.idTurma");
                            $linhas = mysql_num_rows($resultados);

                            if ($linhas > 0) {
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Coordenador</td>
                <td>Turma</td>
                <td>Excluir</td>
               
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idCoordenador");
                                    $nome = mysql_result($resultados, $i, "nome");
                                    $turma = mysql_result($resultados, $i, "turma");
                                    
                                    echo "
                                        <tr>
                                        <td>$id</td>
                                        <td>$nome</td>
                                        <td>$turma</td>
                                            <form method='post' action='#'>
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
    . "alert('É necessário fazer o login!');"
    . "window.location='login.html';"
    . "</script>";
}
?>