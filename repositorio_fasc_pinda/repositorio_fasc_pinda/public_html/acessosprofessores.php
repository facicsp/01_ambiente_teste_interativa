<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
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
        h3{
            color:#000;
        }
        p{
            text-align: right;
            color:#000;
        }
    </style>
</head>
<body>
    <?php
    if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "administrador") {

            //conteudo do site
            //include "topo.php";

            function calculaTempo($hora_inicial, $hora_final) {
                $i = 1;
                $tempo_total;

                $tempos = array($hora_final, $hora_inicial);

                foreach ($tempos as $tempo) {
                    $segundos = 0;

                    list($h, $m) = explode(':', $tempo);

                    $segundos += $h * 3600;
                    $segundos += $m * 60;
                    //$segundos += $s;
                    $segundos += 0;
                    $tempo_total[$i] = $segundos;

                    $i++;
                }
                $segundos = $tempo_total[1] - $tempo_total[2];

                $horas = floor($segundos / 3600);
                $segundos -= $horas * 3600;
                $minutos = str_pad((floor($segundos / 60)), 2, '0', STR_PAD_LEFT);
                $segundos -= $minutos * 60;
                $segundos = str_pad($segundos, 2, '0', STR_PAD_LEFT);

                return $horas . ":" . $minutos;
            }

            function calculatotal($tempos) {
                $segundos = 0;

                foreach ($tempos as $tempo) { //percorre o array $tempo
                    list( $h, $m, $s ) = explode(':', $tempo); //explode a variavel tempo e coloca as horas em $h, minutos em $m, e os segundos em $s
//transforma todas os valores em segundos e add na variavel segundos

                    $segundos += $h * 3600;
                    $segundos += $m * 60;
                    $segundos += $s;
                }

                $horas = floor($segundos / 3600); //converte os segundos em horas e arredonda caso nescessario
                $segundos %= 3600; // pega o restante dos segundos subtraidos das horas
                $minutos = floor($segundos / 60); //converte os segundos em minutos e arredonda caso nescessario
                $segundos %= 60; // pega o restante dos segundos subtraidos dos minutos

                if (strlen($horas) == 1) {
                    $horas = "0" . $horas;
                }
                if (strlen($minutos) == 1) {
                    $minutos = "0" . $minutos;
                }

                return "{$horas}h:{$minutos}min";
            }

            function diaSemana($data) {
                $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
                $diasemana_numero = date('w', strtotime($data));
                return $diasemana[$diasemana_numero];
            }

            $semestre = $_SESSION["semestre"];
            include "conexao2.php";
            /*
            $result = mysqli_query($conexao, "SELECT * FROM docconfig ORDER BY id DESC LIMIT 1");
            
            while ($dados = mysqli_fetch_array($result)) {
                $__header_img = '/' . $dados["header"];
                $__footer_img = '/' . $dados["footer"];
            }
            */
            include "header.php";
            ?>
         

            <div id="titulo">

                <h3>Relatório de Frequência de Professores</h3>


            </div>
            <?php
            $seguranca = new Seguranca;
            $databusca = $seguranca->antisql($_POST["txtData"]);
            $dataformatada = date('d/m/Y', strtotime("+0 days", strtotime($databusca)));
            $diasemana = diaSemana($databusca);
            echo "<p>$diasemana-feira - $dataformatada</p>";

            echo "<table class='relatorio'>"
            . "<tr>"
            . "<td>Nome</td>"
            . "<td>Data</td>"
            . "<td>Entrada</td>"
            . "<td>Saída</td>"
            . "<td>Tempo Total</td>"
            . "</tr>";
            //Localiza todos os professores
            $sql = "select idProfessor,nome from professor order by nome";
            $result = mysqli_query($conexao, $sql);
            $nomeauxiliar = "";
            while ($dados = mysqli_fetch_array($result)) {

                $idProfessor = $dados["idProfessor"];
                $nome = $dados["nome"];

                //Localiza se o professor está lecionando no semestre vigente
                $sql2 = "select idDisciplina from disciplina where idProfessor = '$idProfessor' and semestre = '$semestre'";

                $result2 = mysqli_query($conexao, $sql2);
                $linhas = mysqli_num_rows($result2);
                if ($linhas > 0) {

                    //Se encontrou disciplinas, assim buscaremos os acessos desse professor
                    $sql3 = "SELECT a.*,date_format(a.data,'%d/%m/%Y')as data2
                            FROM acessoprofessor a
                            WHERE a.idprofessor = '$idProfessor'
                            AND a.data = '$databusca'	
                            ORDER BY idacesso";

                    $result3 = mysqli_query($conexao, $sql3);
                    if (mysqli_num_rows($result3) > 0) {
                        $horas = "";
                        $i = 0;
                        while ($dados3 = mysqli_fetch_array($result3)) {

                            $data = $dados3["data2"];
                            $horaentrada = $dados3["horaentrada"];
                            $horasaida = $dados3["horasaida"];
                            $hora1 = substr($horaentrada, 0, 2);
                            $hora2 = substr($horasaida, 0, 2);
                            $minuto1 = substr($horaentrada, 3, 2);
                            $minuto2 = substr($horasaida, 3, 2);
                            $tempo = "";
                            if ($hora1 > $hora2) {
                                
                            } else {
                                $time = calculaTempo($horaentrada, $horasaida);
                                $time = explode(":", $time);
                                $tempo = "$time[0]" . "h:$time[1]" . "min.";
                                $horas[$i] = "$time[0]:$time[1]:00";
                            }
                            /* if ($hora1 == $hora2) {
                              $minutos = $minuto2 - $minuto1;
                              $tempo = $minutos . " minutos";
                              } else if ($hora1 > $hora2) {
                              $hora1 = 24 - $hora1;
                              $hora2 = $hora2 - 0;
                              } */
                            echo "<tr>";

                            if ($nome != $nomeauxiliar) {
                                echo "<td style='border-bottom:1px solid white;border-right:1px solid #ccc;'>$nome</td>";
                            } else {
                                echo "<td style='border-bottom:1px solid white;border-right:1px solid #ccc;'></td>";
                            }
                            $nomeauxiliar = $nome;

                            echo "<td>$data</td>"
                            . "<td>$horaentrada</td>"
                            . "<td>$horasaida</td>"
                            . "<td>$tempo</td>"
                            . "</tr>";
                            $i++;
                        }
                        if ($horas != "") {
                            $tempototal = calculatotal($horas);
                        } else {
                            $tempototal = "Sem registro de tempo";
                        }
                        echo "<tr>"
                        . "<td  style='background-color:#ccc;text-align:right;' colspan='4'><strong>TOTAL:</strong></td>"
                        . "<td  style='background-color:#ccc;'><strong>$tempototal</strong></td>"
                        . "</tr>";
                        unset($horas);
                    } else {
                        echo "<tr>"
                        . "<td>$nome</td>"
                        . "<td colspan='3'>Nenhum acesso encontrado.</td>"
                        . "</tr>";
                    }
                }
            }
            ?>
            <script>
                print();
            </script>
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