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
            if ($_SESSION["tipo"] == "professor") {
                $idAluno = $_SESSION["id"];
                include "topo.php";
                ?>


        <div class="dados">
            <?php
              
                        include "conexao.php";
                        $seguranca = new Seguranca();
                        $idDisciplina = $seguranca->antisql($_GET["id"]);
                        $idDisciplina = $_SESSION["idDisciplina"][$idDisciplina];
            ?>
            <div class="barratitulo"><h1>Registrar Notas - DS I</h1></div>
            <?php
                        if ($_SESSION["tipo"] == "professor") {
                            $sql = "select usuario.nome,usuario.idUsuario from usuario,matricula where usuario.idUsuario = matricula.idAluno and matricula.idTurma in(select idTurma from disciplina WHERE idDisciplina = '$idDisciplina')
";
                        } 
                        //echo $sql;
                        $resultados = mysql_query($sql);
                        $linhas = mysql_num_rows($resultados);
                        if ($linhas > 0) {
                        echo "<form method='post' action='gravarNota.php'>"
                            . "<input type='hidden' name='disciplina' value='$idDisciplina'>";
                            
                            echo "<table border='1' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Aluno</td>
                <td>Nota</td>
                
                
                </tr>
";
                            $registros = 0;
                            for ($i = 0; $i < $linhas; $i++) {
                                $nome = mysql_result($resultados, $i, "nome");
                                $idUsuario = mysql_result($resultados, $i, "idUsuario");
                                
                                
                                echo "
                      <tr>
                      <td>$nome</td>
                      <td><input type='text' name='txtNota$i'></td>
                
                      </tr>";
                      echo "<input type='hidden' name='idAluno$i' value='$idUsuario'>";
                      $registros++;
                        }
                        echo "</table><input type='hidden' name='registros' value='$registros'>"
                            . "<div style='text-align:center;'>Tipo de Avaliação:"
                                . "<select name='tipo'>"
                                . "<option value='1BI'>1BI</option>"
                                . "<option value='2BI'>2BI</option>"
                                . "<option value='EX'>EX</option>"
                                . "<option value='SUB'>SUB</option>"
                                . "</select>"
                                . "<input type='image' src='imagens/gravar.png'></div>"
                        . "</form>";
                        } else {
                            echo "Nenhuma registro encontrado.";
                        }
                        ?>

                
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