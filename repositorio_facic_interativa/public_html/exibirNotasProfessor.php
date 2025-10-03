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
            if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "administrador") {
                if ($_SESSION["tipo"] == "professor"){
                $idProfessor = $_SESSION["id"];
                }
                include "topo.php";
                include "conexao.php";
                $seguranca = new Seguranca();
                $idDisciplina = $seguranca->antisql($_GET["id"]);
                $disciplina = $_SESSION["disciplina"][$idDisciplina];
                $idDisciplina = $_SESSION["idDisciplina"][$idDisciplina];
                if ($_SESSION["tipo"] == "administrador"){
                    $idProfessor = $_SESSION["idProfessor"][$idDisciplina];
                }
                ?>


                <div class="dados">
                    <div class="barratitulo"><h1>Minhas Notas</h1></div>


        <?php
        $sql = "select aula.descricao from atividade,aula where atividade.iddisciplina = '$idDisciplina'  and atividade.idaula = aula.idaula group by atividade.idAula order by atividade.idAula";
        $result = mysql_query($sql);
        $linhas = mysql_num_rows($result);
        $quantidadeAtividades = $linhas;
        //echo $sql;
        if ($linhas > 0) {
            $colunas = $linhas + 2;
            echo "<table border='1' align='center'>"
            . "<tr><td colspan='$colunas' style='background-color:#FFD324;color:#069;text-align:center;'>$disciplina</td></tr>";
            echo "<tr>"
                . "<td>Alunos</td>";
            for ($i = 0; $i < $linhas; $i++) {
                $soma = 0;
                
                
                $descricao = mysql_result($result, $i, "descricao");
                echo"<td>$descricao</td>";
            }
            echo "<td>Média</td></tr>";
            $sql = "select atividade.*,usuario.nome,atividade.nota from atividade,usuario where iddisciplina = '$idDisciplina' and atividade.idaluno = usuario.idusuario order by nome,idaula";
            //echo $sql;
            $result = mysql_query($sql);
            $linhas = mysql_num_rows($result);
            if($linhas > 0){
                for($i = 0;$i < $linhas;$i++){
                    $nome[$i] = mysql_result($result, $i, "nome");
                    $nota[$i] = mysql_result($result, $i, "nota");
                }
                
                for($i = 0;$i < sizeof($nota);$i++){
                    echo "<tr>"
                    . "<td>$nome[$i]</td>";
                    for($n=0;$n<$quantidadeAtividades;$n++){
                        echo "<td>$nota[$i]</td>";
                        $soma += $nota[$i];
                        $i++;
                    }
                    $media = $soma/$quantidadeAtividades;
                    $total = number_format($media, 2, ",", " ");
                    $i--;
                    echo "<td>$total</td></tr>";
                    $soma = 0;
                }
                
                
                
            }else{
                echo "Nenhuma registro encontrado.";
            }
            
        }else {
            echo "Nenhuma registro encontrado.";
        }
        ?>

                </table>
                <hr>
                <div class="voltar"><a href="registros.php"><img src="imagens/voltar.png">Voltar</a></div>

            </div>
        </form>
        <hr>


        </body>
        </html>
        <?php
        }else {
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