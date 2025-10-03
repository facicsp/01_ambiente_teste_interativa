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
            if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "administrador") {
                if ($_SESSION["tipo"] == "professor") {
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
                    <div class="barratitulo"><h1>Frequência</h1></div>


                    <?php
                    //$sql = "select date_format(frequencia.data,'%d/%m/%Y')as data from frequencia where iddisciplina = '$idDisciplina' group by idaula order by data desc";
                    
                    $sql = "select u.nome,u.acesso from usuario u,matricula m
where m.idTurma IN(select idTurma from disciplina where idDisciplina = '$idDisciplina')
AND m.idAluno = u.idUsuario ORDER BY u.nome";
                    $result = mysql_query($sql);
                    $linhas = mysql_num_rows($result);
                    //$quantidadeAulas = $linhas;
                    //echo $sql;
                      echo "<table border='1' align='center'>"
                        . "<tr style='background-color:#FFD324;color:#069;text-align:center;'><td>Alunos</td><td>Acessos</td></tr>";
                      
                    if ($linhas > 0) {
                        for($i=0;$i<$linhas;$i++){
                            $nome = mysql_result($result, $i, "nome");
                            $acesso = mysql_result($result, $i, "acesso");
                            echo "<tr><td>$nome</td>"
                            . "<td>$acesso</td></tr>";
                        }
                    }
                    
                    //adaptados
                    $sql = "select idAluno from listadisciplina WHERE listadisciplina.idDisciplina = '$idDisciplina'";
                    $result = mysql_query($sql);
                    $linhas = mysql_num_rows($result);
                    
                    if ($linhas > 0) {
                        for($i=0;$i<$linhas;$i++){
                            
                            $idUsuario = mysql_result($result, $i, "idAluno");
                            $resultUsuario = mysql_query("SELECT nome, acesso FROM usuario WHERE idUsuario = $idUsuario");
                            
                            $nome = mysql_result($resultUsuario, 0, "nome");
                            $acesso = mysql_result($resultUsuario, 0, "acesso");
                            echo "<tr><td>$nome</td>"
                            . "<td>$acesso</td></tr>";
                        }
                    }
//adptados
                    
                        //$colunas = $linhas + 2;
                        
                        /*$sql = "select frequencia.*,usuario.nome from frequencia,usuario where iddisciplina = '$idDisciplina' and frequencia.idaluno = usuario.idusuario group by usuario.nome order by nome,data";
                        //echo $sql;
                        $result = mysql_query($sql);
                        $linhas = mysql_num_rows($result);
                        if ($linhas > 0) {
                            $porcentagem = 100 / $quantidadeAulas;
                            for ($i = 0; $i < $linhas; $i++) {
                                $nome[$i] = mysql_result($result, $i, "nome");
                                $nome2[$i] = mysql_result($result, $i, "nome");
                                $frequencia[$i] = mysql_result($result, $i, "frequencia");
                            }
                            $presenca = 0;
                            for ($i = 0; $i < sizeof($frequencia); $i++) {
                                echo "<tr>"
                                . "<td>$nome2[$i]</td>";
                                for ($n = 0; $n < $quantidadeAulas; $n++) {
                                    echo "<td>$frequencia[$i]</td>";
                                    if ($frequencia[$i] == "P") {
                                        $presenca++;
                                    }
                                    $i++;
                                }
                                $total = $porcentagem * $presenca;
                                $total = number_format($total, 2, ",", " ");
                                $i--;
                                echo "<td>$total</td></tr>";
                                $presenca = 0;
                            }
                        } else {
                            echo "Nenhuma registro encontrado.";
                        }
                    } else {
                        echo "Nenhuma registro encontrado.";
                    }*/
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