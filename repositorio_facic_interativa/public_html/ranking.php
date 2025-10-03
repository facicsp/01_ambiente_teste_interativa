<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ranking</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--[if lt IE 9]>
          <script src="./assets/javascripts/html5.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="./assets/stylesheets/demo.css" />
        <!--[if (gt IE 8) | (IEMobile)]><!-->
        <link rel="stylesheet" href="./assets/stylesheets/unsemantic-grid-responsive.css" />
        <!--<![endif]-->
        <!--[if (lt IE 9) & (!IEMobile)]>
          <link rel="stylesheet" href="./assets/stylesheets/ie.css" />
        <![endif]-->

<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
        <script src="js/jquery.min.js"></script>
        <script src="js/skel.min.js"></script>

        <script src="js/init.js"></script>
        <noscript>
        <link rel="stylesheet" href="css/skel.css" />
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/style-xlarge.css" />
        </noscript>

        <style>
            .trilha{
                width:1200px;
                border:1px solid black;
                height:614px;
                background-image: url(imagens/mapa3.jpg);
                background-repeat: repeat-x;


            }
            /*            
                        .quadro3{
                            
                            padding:3px;
                            width:50px;
                            color:#cc0033;
                            transform: translateY(250px) translateX(200px);
                            
                        }
            */
            .quadro4{

                padding:3px;
                width:50px;
                color:#cc0033;
                transform: translateY(150px) translateX(600px);

            }
            .hint {
                width:200px;
                background-color: #FFFBE4;
                border-radius: 3px;
                border: 1px solid #CCC;
                box-shadow: 1px 1px 3px rgba(0,0,0,0.2);
                display: inline-block;
                font-size: 80%;
                margin-left: 20px;
                padding: 3px;
                opacity: 0;

                transition: opacity 1s, margin-left 0.5s;
                /*transition: all 1s;*/
            }

            .quadro5{
                position:fixed;
                top:10px;
                left:450px;

            }

            .quadro3:hover .hint{
                margin-left: -5px;
                opacity: 1;
                color:#ff6600;
            }
            .quadro4:hover .hint{
                margin-left: -5px;
                opacity: 1;
                color:#ff6600;
            }

        </style>

    </head>
    <body>

        <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "aluno" || $_SESSION["tipo"] == "professor") {
                $idAluno = $_SESSION["id"];
                include "topo.php";
                echo "<div class='trilha'>";
                include "conexao.php";
                mysql_set_charset("utf-8");
                if (isset($_GET["idDisciplina"])) {
                    $idDisciplina = $_GET["idDisciplina"];
                    $sql = "select idusuario,nome from usuario,matricula where matricula.idturma in(select idturma from disciplina where iddisciplina = '$idDisciplina') and matricula.idaluno = usuario.idusuario";
                    //echo $sql;
                    $result = mysql_query($sql);
                    $linhas = mysql_num_rows($result);
                    if ($linhas > 0) {
                        for ($i = 0; $i < $linhas; $i++) {
                            $idUsuario = mysql_result($result, $i, "idUsuario");
                            $nome = mysql_result($result, $i, "nome");
                            //Verifica Extras
                            $sqlExtra = "select sum(pontos)as pontos from extra where idAluno = '$idUsuario' and idDisciplina = '$idDisciplina'";
                            $resultExtra = mysql_query($sqlExtra);
                            $linhasExtra = mysql_num_rows($resultExtra);
                            $pontosExtras = 0;
                            if($linhasExtra > 0){
                                $pontosExtras = mysql_result($resultExtra, 0, "pontos");
                            }
                            
                            //Fim do Verifica Extras
                            
                            
                            //Verifica a Frequencia
                            $sqlFrequencia = "SELECT frequencia from pontosfrequencia WHERE idAluno = '$idUsuario' AND idDisciplina = '$idDisciplina'";
                            $resultFrequencia = mysql_query($sqlFrequencia);
                            $totalFrequencia = 0;
                            if (mysql_num_rows($resultFrequencia) > 0) {
                                $frequencia = mysql_result($resultFrequencia, 0, "frequencia");
                                $totalFrequencia = (500 * $frequencia) / 100;
                            }


                            //Fim do Verifica a Frequencia
                            $sqlPontos = "select usuario.nome,sum(atividade.nota) as pontos from atividade,aula,usuario where atividade.idaluno = '$idUsuario' and atividade.iddisciplina = '$idDisciplina' and atividade.idaula = aula.idaula and atividade.idaluno = usuario.idusuario and atividade.data > '2016-10-01'";
                            //echo $sqlPontos;
                            $resultPontos = mysql_query($sqlPontos);
                            //echo $sqlPontos;
                            $pontos = mysql_result($resultPontos, 0, "pontos");
                            $nome2 = substr($nome, 0, 3);
                            $pontos +=$totalFrequencia+$pontosExtras;
                            //$ranking["$pontos"] = "$nome";
                            $ranking["$nome"] = "$pontos";
                            htmlentities($nome2);
                            if ($pontos > 0) {
                                $posicaox = (($pontos * 1150) / 6000) - 50;

                                $posicaoy = rand(20, 560);
                                if ($idAluno == $idUsuario) {
                                    $cor = "color9";
                                } else {
                                    $cor = "color11";
                                }

                                echo "<div class='quadro3' style='position:absolute;padding:3px;width:50px;color:#cc0033;top:" . $posicaoy . "px; left:" . $posicaox . "px;'>
                <i class='icon small rounded $cor fa-map-marker'>$nome2</i><span class='hint'>$nome - $pontos XP</span>
             </div>";
                            }
                        }
                    }
                }
                ?>





            </div>
                <?php
                echo "<h3>Ranking</h3>";
                //krsort($ranking);
                arsort($ranking);
                $ordem = 1;
                
                
                foreach ($ranking as $valor => $chave) {    
                    if($chave < 2000){
                    echo "<div class='ranking' style='background-color:red;'>$ordem - $valor - $chave</div>";
                    }else{
                    echo "<div class='ranking'>$ordem - $valor - $chave</div>";
                    }
                    $ordem++;
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
        echo "<script>"
        . "alert('É necessário fazer o login!');"
        . "window.location='login.html';"
        . "</script>";
    }
    ?>