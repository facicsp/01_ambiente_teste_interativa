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
                //atividade do site
                include "topo.php";include "conexao.php";
                $seguranca = new Seguranca();

                $idDisciplina = $seguranca->antisql($_POST["id"]);
                $result = mysql_query("SELECT * FROM disciplina WHERE idDisciplina = '$idDisciplina'");
                $idTurma = mysql_result($result, 0, "idTurma");
                $turma = mysql_result($result, 0, "disciplina");
?>

        <table width="950px" align="center" id="tabelaprincipal">

            <tr>
                <td>
                    <div id="titulo">
                        <?php
                        echo "<h3>Matriculados - $turma</h3>";
                        ?>
                    </div>
                    
                    <hr>
                    
                    <?php
                    
                    $sql = "select u.idUsuario,u.ra,u.nome,t.turma 
                        from usuario u,matricula m,turma t
                        where m.idTurma = '$idTurma'
                        and m.idAluno = u.idUsuario
                        and m.idTurma = t.idTurma
                        ORDER BY u.nome";
                    //echo $sql;
                    $resultados = mysql_query($sql);
                    $linhas = mysql_num_rows($resultados);
                    
                    echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                    <tr>
                    <td>RA</td>
                    <td>Nome</td>
                    <td>Turma</td>
                    <td>Acessos</td>
                    </tr>";
                    
                    if ($linhas > 0) {
                        for ($i = 0; $i < $linhas; $i++) {
                            $id = mysql_result($resultados, $i, "idUsuario");
                            $ra = mysql_result($resultados, $i, "ra");
                            $nome = mysql_result($resultados, $i, "nome");
                            $turma = mysql_result($resultados, $i, "turma");
                            
                            echo "
                      <tr>
                      <td>$ra</td>
                      <td>$nome</td>
                      <td>$turma</td>
                      <td><a href='exibirAcessos.php?idAluno=$id' target='_blank' class='botao' style='color:#FFF;'>Acessos</a></td>
                      </tr>
";
                            }
                        }

                        $resultados = mysql_query("SELECT idUsuario, ra, nome FROM listadisciplina 
                          LEFT JOIN usuario ON usuario.idUsuario = listadisciplina.idAluno
                          WHERE idDisciplina = '$idDisciplina'");
$linhas = mysql_num_rows($resultados);

for ($i = 0; $i < $linhas; $i++) {
  $id = mysql_result($resultados, $i, "idUsuario");
  $ra = mysql_result($resultados, $i, "ra");
  $nome = mysql_result($resultados, $i, "nome");
  $turma = "Adaptado";
  
  echo "
<tr>
<td>$ra</td>
<td>$nome</td>
<td>$turma</td>
<td><a href='exibirAcessos.php?idAluno=$id' target='_blank' class='botao' style='color:#FFF;'>Acessos</a></td>
</tr>
";
  }


                        echo "</table>";

                        ?>
                </td>
            </tr>

        </table>

    </body>
</html>
 <?php } else {
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