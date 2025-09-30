<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>


    <link rel="stylesheet" href="css/cadastro.css">
    <style>
    .dados td {
        width: 0 !important;
        text-align: center !important;
        padding: 8px !important;
        vertical-align: middle !important;
    }

    .semestre {
        background: #00669957;
        color: #294175;
    }

    @media print {
        .dados {
            width: 100%;
            margin-left: 0;
            margin-right: 0;
        }
        .barratopo, .voltar {
            display: none;
        }
        * {
            color: #000 !important;
        }
    }
    </style>
</head>

<body>
    <?php
        if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "administrador") {
            include "topo.php";
            include "LoginRestrito/conexao.php";
            include "funcaoNotas.php";
            $seguranca = new Seguranca();
            $idAluno = $seguranca->antisql($_GET["id"]);
        } else {
            exit("<script>alert('É necessário fazer o login!'); window.location='login.html'; </script>");
        }

        $resultAluno = mysqli_query($conexao, "SELECT nome, ra FROM usuario WHERE idUsuario = '$idAluno'");
        $aluno = mysql_result($resultAluno, 0, "nome");
        $ra = mysql_result($resultAluno, 0, "ra");
    ?>
    <div class="dados">
        <div class="barratitulo">
            <h2>Histórico de Notas</h2>
            <h2><?= $ra . " - " . $aluno ?></h2>
        </div>
        <?php
                    function getHeader($semestre) {
                        return "<tr><td colspan='10' style='height: 40px; border: none;'></td></tr>
                        <tr style='background-color:#FFD324;color:#069;text-align:center;'>
                        <td rowspan='2'>Disciplinas ($semestre)</td>
                        <td colspan='2'>1° Bimestre</td>
                        <td colspan='2'>2° Bimestre</td>
                        <td rowspan='2'>SUB</td>
                        <td rowspan='2'>EXAME</td>
                        <td colspan='3'>Média</td>
                      </tr>
                      <tr style='background-color:#FFD324;color:#069;text-align:center;'>
                        <td>P1</td>
                        <td>A. Virtual l</td>
                        <td>P2</td>
                        <td>A. Virtual ll</td>
                        <td>1 Bim</td>
                        <td>2 Bim</td>
                        <td>Final</td>
                      </tr>";
                    }
                    
                    $sqlDisciplina = "SELECT disciplina.disciplina, disciplina.semestre, disciplina.idDisciplina, boletim.* FROM boletim INNER JOIN disciplina ON disciplina.idDisciplina = boletim.idDisciplina WHERE boletim.idaluno = '$idAluno' ORDER BY disciplina.semestre DESC";
                    $resultDisciplina = mysqli_query($conexao, $sqlDisciplina);
                    $linhasDisciplina = mysqli_num_rows($resultDisciplina);
                    // echo $linhasDisciplina;
                    if ($linhasDisciplina > 0) {


                      echo "<table border='1' align='center'>";

                        $semestreAux = "";

                        for ($i = 0; $i < $linhasDisciplina; $i++) {
                            $soma = 0;
                            $porcentagem = 0;
                            $media = 0;
                            $status = "Andamento";
                            $idDisciplina = mysql_result($resultDisciplina, $i, "idDisciplina");
                            $disciplina = mysql_result($resultDisciplina, $i, "disciplina");
                            $bimestre1 = mysql_result($resultDisciplina, $i, "bimestre1");
                            $bimestre2 = mysql_result($resultDisciplina, $i, "bimestre2");
                            $exame = mysql_result($resultDisciplina, $i, "exame");
                            $sub = mysql_result($resultDisciplina, $i, "sub");
                            $t1 = mysql_result($resultDisciplina, $i, "t1");
                            $t2 = mysql_result($resultDisciplina, $i, "t2");
                            $semestre = mysql_result($resultDisciplina, $i, "semestre");
                            $media = ($bimestre1 + $bimestre2 + $t1 + $t2) / 2;

                            $forumBm1 = getForum($idAluno, $idDisciplina, 1);
                            $forumBm2 = getForum($idAluno, $idDisciplina, 2);

                            $questionario1 = getProva($idAluno, $idDisciplina, 1);
                            $questionario2 = getProva($idAluno, $idDisciplina, 2);

                            $moduloBm1 = getModulo($idAluno, $idDisciplina, 1);
                            $moduloBm2 = getModulo($idAluno, $idDisciplina, 2);

                            $t1 = ($forumBm1 + $questionario1 + $moduloBm1);
                            $t2 = ($forumBm2 + $questionario2 + $moduloBm2);
                            
                            $virtual1 = $t1 == 0 ? 0 : number_format($t1, 1, '.', '');
                            $virtual2 = $t2 == 0 ? 0 : number_format($t2, 1, '.', '');
                          
                            $p1 = getProva($idAluno, $idDisciplina, 10);
                            $p2 = getProva($idAluno, $idDisciplina, 20);
                            $psub = getProva($idAluno, $idDisciplina, 30);
                            $pexa = getProva($idAluno, $idDisciplina, 40);

                            if ($p1 !== false) $bimestre1 = $p1;
                            if ($p2 !== false) $bimestre2 = $p2;
                            if ($psub !== false) $sub = $psub;
                            if ($pexa !== false) $exame = $pexa;
                          
                            //$bimestre1 = $bimestre1 < 7 ? 7 : $bimestre1;
                              
                            // echo "#: $idDisciplina <br>forum: $forumBm2 <br>questionario: $questionario2 <br>modulo: $moduloBm2 <br>AV: $virtual2";

                              $bim1 = $bimestre1 + $t1; //($bimestre1 + $t1) == 0 ? 0 : number_format(($bimestre1 + $t1), 1, '.', '');
                              $bim2 = $bimestre2 + $t2; //($bimestre2 + $t2) == 0 ? 0 : number_format(($bimestre2 + $t2), 1, '.', '');
                              
                          $bim1 = $bim1 < 10 ? $bim1 : 10;
                          $bim2 = $bim2 < 10 ? $bim2 : 10;
                         
                          $media = ($bim1 + $bim2) / 2;

                              if ($media < 7) {
                                // p1 foi maior que a p2 && a p2 foi menor que a sub
                                if ($bimestre1 >= $bimestre2 && $bimestre2 < $sub) {
                                    $media = ($bimestre1 + $sub + $t1 + $t2) / 2;
                                // p2 foi maior que a p1 && a p1 foi maior que a sub
                                } else if ($bimestre1 < $sub) {
                                    $media = ($bimestre2 + $sub + $t1 + $t2) / 2;
                                }
                              }
                              
                              if (($media >= 7 && ($exame == "" || $exame == 0 || $exame == null)) || ($media + $exame >= 10)) { //&& $exame > $media
                                  if ($exame != "" && $exame > 0) {
                                      $media = ($media + $exame) / 2;
                                  }
                                $status = 'Aprovado';
                              } else {
                                $status = 'Reprovado';
                              }
                              
                              if ($bimestre2 == 0) {
                                $status = 'Em Andamento';
                                //$media = '';
                              }

                              if ($semestreAux != $semestre) {
                                $semestreAux = $semestre;

                                echo getHeader($semestre);
                              }

                              
                            echo "<tr>
                                <td style='width: 30% !important;'>$disciplina</td>
                                <td>$bimestre1</td>
                                <td>$virtual1</td>
                                <td>$bimestre2</td>
                                <td>$virtual2</td>
                                <td>$sub</td>
                                <td>$exame</td>
                                <td>$bim1</td>
                                <td>$bim2</td>
                                <td><b>$media</b></td>
                              </tr>";

                            //   - $status
                        }
                        echo "</table>";
                    } else {
                        echo "Nenhuma registro encontrado.";
                    }

                    ?>

        </table>
        <hr>
        <div class="voltar"><a href="index.php"><img src="imagens/voltar.png">Voltar</a></div>

    </div>
    </form>
    <hr>
  
  <script>print();</script>


</body>

</html>