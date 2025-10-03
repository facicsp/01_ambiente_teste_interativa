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
            include './conexao.php';
            $seguranca = new Seguranca();
            $id = "";

            if (isset($_GET["idDisciplina"])) {
            $id = $seguranca->antisql($_GET["idDisciplina"]);
            $_SESSION["idDisciplina"] = $id;
            }
            $sql = "select usuario.idUsuario,usuario.nome from usuario,matricula
where idTurma in(select idTurma from disciplina where idDisciplina = '$id') and matricula.idAluno = usuario.idUsuario order by usuario.nome";
            echo $sql;
            $resultados = mysql_query($sql);
            $linhas = mysql_num_rows($resultados);
            if ($linhas > 0) {
            //$listaAlunos[];
            echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Aluno</td>
                <td>Pontos</td>
                <td>Menção</td>
                
                </tr>
";

            for ($i = 0;$i < $linhas;$i++) {
            $idAluno = mysql_result($resultados, $i, "idUsuario");
            $nome = mysql_result($resultados, $i, "nome");
            $sqlPontos = "select sum(pontos)as pontos from extra where idAluno = '$idAluno' and idDisciplina = '$id'";
            //echo $sqlPontos;
            $resultPontos = mysql_query($sqlPontos);
            $pontos = mysql_result($resultPontos, 0, "pontos");

            echo "
                      
                      <tr>
                      <td>$nome</td>"
            . "<td>$pontos</td>";


            $mencao = "I";

            if($pontos >= 900){
            $mencao = "MB";
            }else if($pontos >= 700){
            $mencao = "B";
            }else if($pontos >= 450){
            $mencao = "R";
            }
            
            echo "<td>$mencao</td>";
            $mencao2[$i] = $mencao;
            
            echo "</tr>";
            }
            }
            
            ?>

        </table>



    </div>
</form>
<hr>
<div class="dados">
    <table>
        <?php
        for($i = 0;
        $i < sizeof($mencao2);
        $i++){
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