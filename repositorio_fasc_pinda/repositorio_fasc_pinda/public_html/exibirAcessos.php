<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">

    </head>
    <body>
        <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "administrador") {
            
                    $idAluno = $_GET["idAluno"];
            
                include "topo.php";
                ?>


                <div class="dados">



                    <?php

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

                        return $horas.":".$minutos;
                    }

                    include "LoginRestrito/conexao.php";
                    mysql_set_charset("iso-8859-1");
                    $seguranca = new Seguranca();
                    
                    $sql = "SELECT a.*,date_format(a.data,'%d/%m/%Y')as data2,u.nome "
                            . "FROM acesso a,usuario u "
                            . "WHERE a.idusuario = '$idAluno' "
                            . "AND a.idusuario = u.idUsuario "
                            . "ORDER BY idacesso DESC";
                    $result = mysqli_query($conexao, $sql);
                    $linhas = mysqli_num_rows($result);
                    if ($linhas > 0) {
                        $nome = mysql_result($result, 0, "nome");
                        
                        echo "<div class=\"barratitulo\"><h1>$linhas acesso(s) - $nome</h1></div>";
                        echo "<table>"
                        . "<tr><td>Data</td>"
                        . "<td>Entrada</td>"
                        . "<td>Saída</td>"
                        . "<td>Tempo Total</td>"
                        . "</tr>";
                        for ($i = 0; $i < $linhas; $i++) {
                            $data = mysql_result($result, $i, "data2");
                            $horaentrada = mysql_result($result, $i, "horaentrada");
                            $horasaida = mysql_result($result, $i, "horasaida");
                            $hora1 = substr($horaentrada, 0, 2);
                            $hora2 = substr($horasaida, 0, 2);
                            $minuto1 = substr($horaentrada, 3, 2);
                            $minuto2 = substr($horasaida, 3, 2);
                            if($hora1 > $hora2){
                                
                            }else{
                                $time = calculaTempo($horaentrada, $horasaida);
                                $time = explode(":", $time);
                                $tempo = "$time[0]"."h:$time[1]"."min.";
                            }
                            /*if ($hora1 == $hora2) {
                                $minutos = $minuto2 - $minuto1;
                                $tempo = $minutos . " minutos";
                            } else if ($hora1 > $hora2) {
                                $hora1 = 24 - $hora1;
                                $hora2 = $hora2 - 0;
                            }*/
                            echo "<tr><td>$data</td>"
                            . "<td>$horaentrada</td>"
                            . "<td>$horasaida</td>"
                            . "<td>$tempo</td>"
                            . "</tr>";
                        }
                    } else {
                        echo "<p style='color:#000;'>Nenhum acesso encontrado.</p>";
                    }
                    ?>

                </table>
                <hr>
                
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