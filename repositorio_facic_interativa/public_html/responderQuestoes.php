<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
        

        <link rel="stylesheet" href="css/cadastro.css">
        <style>
            label{
                color:#069;
            }
            p{    
              display: flex;
              align-items: center;
              text-align: left;
              color: #000;
              padding: 0 10px !important;
            }
            input[type="radio"]{
                margin-right: 10px;
                border: 0px;
                height: 1em;
                zoom: 1.3;
                -moz-transform: scale(1.3);
                -moz-transform-origin: 0 0;
                -o-transform: scale(1.3);
                -o-transform-origin: 0 0;
                -webkit-transform: scale(1.3);
                -webkit-transform-origin: 0 0;
                transform: scale(1.3); /* Standard Property */
                transform-origin: 0 0;  /* Standard Property */
            }
        </style>
    </head>
    <body>
        <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "aluno") {
                $idAluno = $_SESSION["id"];
                $idDisciplina = $_SESSION["disciplina"];
                include "topo.php";
                
                ?>
        
        
        <div class="dados">
            <div class="barratitulo"><h1>Questionário</h1></div>
            
            <?php
            include "conexao.php";

            $seguranca = new Seguranca();
            $idAplicar = $seguranca->antisql($_POST["id"]);

            $sql = "SELECT * FROM aplicarprova WHERE idDisciplina = '$idDisciplina' AND idAplicarProva = '$idAplicar'";
            $resultados = mysql_query($sql);
            $linhas = mysql_num_rows($resultados);

            // echo "<h1>$sql</h1>";

            if ($linhas == 1) {
              $data = mysql_result($resultados, 0, 'fechamento');
              $idProva = mysql_result($resultados, 0, 'idProva');

              $result = mysql_query("SELECT idAluno FROM respostas 
                                    LEFT JOIN questao ON questao.idQuestao = respostas.idQuestao 
                                    LEFT JOIN aplicarprova ON aplicarprova.idProva = questao.idProva 
                                    WHERE respostas.idAluno = '$idAluno' AND aplicarprova.idAplicarProva = '$idAplicar'");


              if (mysql_num_rows($result) > 0 || strtotime(date("Y-m-d")) > strtotime($data)) {
                echo "<script>window.location = 'responder.php';</script>"; 
                exit;
              }
              
              $result = mysql_query("SELECT * FROM questao WHERE idProva = '$idProva' ORDER BY RAND() LIMIT 10");
              echo "<form method='post' action='gravarResponderQuestionario.php'>";
              for ($i=0; $i<mysql_num_rows($result); $i++) {

                $idQuestao = mysql_result($result, $i, 'idQuestao');
                $descricao = mysql_result($result, $i, 'descricao');
                $a = mysql_result($result, $i, 'a');
                $b = mysql_result($result, $i, 'b');
                $c = mysql_result($result, $i, 'c');
                $d = mysql_result($result, $i, 'd');
                $e = mysql_result($result, $i, 'e');
                $f = mysql_result($result, $i, 'f');
                $g = mysql_result($result, $i, 'g');
                $h = mysql_result($result, $i, 'h');

                echo "<div class='pergunta'>
                        <br>
                        <p><b>".($i+1).")</b> &nbsp; $descricao</p>
                        <input required value='$idQuestao' type='hidden' name='txtIdQuestao[$i]'>
                        <p><input required value='0' type='radio' name='txtResposta[$i]'>$a</p>
                        <p><input required value='1' type='radio' name='txtResposta[$i]'>$b</p>";
                echo $c == "" ? "" : "<p><input required value='2' type='radio' name='txtResposta[$i]'>$c</p>";
                echo $d == "" ? "" : "<p><input required value='3' type='radio' name='txtResposta[$i]'>$d</p>";
                echo $e == "" ? "" : "<p><input required value='4' type='radio' name='txtResposta[$i]'>$e</p>";
                echo $f == "" ? "" : "<p><input required value='5' type='radio' name='txtResposta[$i]'>$f</p>";
                echo $g == "" ? "" : "<p><input required value='6' type='radio' name='txtResposta[$i]'>$g</p>";
                echo $h == "" ? "" : "<p><input required value='7' type='radio' name='txtResposta[$i]'>$h</p>";
                echo "</div>";
              }
              echo "<input type='submit' value='Finalizar'></form><br><hr>";

            }
            ?>

            </div>
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