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
<!--<table width="950px" align="center" id="tabelaprincipal">-->
                    <form method="post" action="dadosAulas.php">
                        <table>
                        <tr>
                            <td>
                                Disciplina:
                            </td>
                            <td>
                                <select name="txtDisciplina">
                                    <option>::Escolha uma disciplina::</option>
                                    <?php
                                    include './conexao.php';
                                    $seguranca = new Seguranca();
                                    if($_SESSION["tipo"] == "professor"){
                                            $idProfessor = $_SESSION["id"];
                                            $sql = "SELECT * FROM disciplina WHERE idProfessor ='$idProfessor' AND semestre = '". $_SESSION['semestre'] ."' ORDER BY disciplina";
                                            }else{
                                                $sql = "SELECT * FROM disciplina WHERE semestre = '". $_SESSION['semestre'] ."' ORDER BY disciplina";
                                            }
                                    //echo $sql;
                                    $resultados = mysql_query($sql);
                                    $linhas = mysql_num_rows($resultados);
                                    $listaDisciplina = [];
                                    if ($linhas > 0) {
                                        for ($i = 0; $i < $linhas; $i++) {
                                            $iddisciplina = mysql_result($resultados, $i, "iddisciplina");
                                            $disciplina = mysql_result($resultados, $i, "disciplina");
                                            $listaDisciplina[] = $iddisciplina;
                                            echo "<option value='$iddisciplina'>$disciplina</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>

                            </td>
                        </tr>
                </table>
                        <hr>
        <div class="dados">
            
                        <?php
                        $consulta = "";
                        if (isset($_POST["txtDisciplina"])) {
                            $consulta = $seguranca->antisql($_POST["txtDisciplina"]);
                            $consultaSql = "AND idDisciplina = '$consulta'";
                            /*
                            echo "<a href='registrarPontoFrequencia.php?idDisciplina=$consulta' class='botao'>Preencher Frequência</a>";
                            echo "<br><br>";
                            //echo "<a href='verificarPontosExtras.php?idDisciplina=$consulta' class='botao'>Verificar Pontos Extras</a>";
                            //echo "<br><br>";
                            echo "<a href='ranking.php?idDisciplina=$consulta' target='_blank' class='botao'>Ver Mapa</a>"
                                    . "<br><br>";*/
                        }
                        if ($_SESSION["tipo"] == "professor") {
                            $sql = "select aula.idAula,aula.idDisciplina,aula.descricao,date_format(dataAula,'%d/%m/%Y')as dataAula,date_format(dataAtividade,'%d/%m/%Y') as dataAtividade from aula where idDisciplina IN (".implode($listaDisciplina, ',').") $consultaSql ORDER BY dataAtividade DESC";
                        } else {
                            $sql = "SELECT aula.* FROM aula WHERE nome LIKE '%$consulta%' AND idDisciplina IN (".implode($listaDisciplina, ',').") ORDER BY idAula DESC";
                        }
                        //echo $sql;
                        $resultados = mysql_query($sql);
                        $linhas = mysql_num_rows($resultados);
                        if ($linhas > 0) {
                            echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Título</td>
                <td>Data da Aula</td>
                <td>Data de Entrega da Atividade</td>
                <td>Visualizar Atividades</td>
                </tr>
";

                            for ($i = 0; $i < $linhas; $i++) {
                                $idAula = mysql_result($resultados, $i, "idAula");
                                $descricao = mysql_result($resultados, $i, "descricao");
                                $dataAula = mysql_result($resultados, $i, "dataAula");
                                $dataAtividade = mysql_result($resultados, $i, "dataAtividade");
                                $idDisciplina = mysql_result($resultados,$i,"idDisciplina");
                                if($dataAtividade == "00/00/0000" || $dataAtividade == "19/10/1998" || $dataAtividade == "01/01/2000"){
                                    $dataAtividade = "Não há atividade.";
                                }
                                echo "
                      <form method='post' action='alterarAula.php'>
                      <tr>
                      <td>$descricao</td>
                      <td>$dataAula</td>
                      <td>$dataAtividade</td>";
                      if($dataAtividade == "Não há atividade."){
                          echo "<td>-</td>";
                      }else{
                      echo "<td><a href='visualizarAtividades.php?id=$idAula&idDisciplina=$idDisciplina'  target='_blank'><img src='imagens/atividades.png'>Visualizar Atividades</a>
                          
                            </td>";
                      
                      }    
                      echo "</tr>";
                        }
                        } else {
                            echo "Nenhuma registro encontrado.";
                        }
                        ?>

                </table>
                


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