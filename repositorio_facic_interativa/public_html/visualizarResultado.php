<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

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
              width: 20px;
              height: 20px;
              margin-right: 10px;
            }
            .green{
              background-color: rgba(0, 128, 0, .2) !important;
            }
            .red{
              background-color: rgba(255, 0, 0, .2);
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
            include "LoginRestrito/conexao.php";

            $seguranca = new Seguranca();
            $idAplicar = $seguranca->antisql($_POST["id"]);


            $sql = "SELECT * FROM aplicarprova WHERE idDisciplina = '$idDisciplina' AND idAplicarProva = '$idAplicar'";
            $resultados = mysqli_query($conexao, $sql);
            $linhas = mysqli_num_rows($resultados);

            if (mysqli_num_rows($resultados) == 1) {
              $data = mysql_result($resultados, 0, 'data');
              $idProva = mysql_result($resultados, 0, 'idProva');

//               SELECT descricao, a,b,c,d,e,f,g,h, resposta, correta FROM respostas 
// LEFT JOIN questao ON questao.idQuestao = respostas.idQuestao 
// LEFT JOIN aplicarprova ON aplicarprova.idProva = questao.idProva 
// WHERE respostas.idAluno = 903 AND aplicarprova.idAplicarProva = 3

              $result = mysqli_query($conexao, "SELECT descricao, a,b,c,d,e,f,g,h, resposta, correta, questao.idQuestao FROM respostas 
                                    LEFT JOIN questao ON questao.idQuestao = respostas.idQuestao 
                                    LEFT JOIN aplicarprova ON aplicarprova.idProva = questao.idProva 
                                    WHERE respostas.idAluno = '$idAluno' AND aplicarprova.idAplicarProva = '$idAplicar'");


              if (mysqli_num_rows($result) == 0) {
                echo "<script>window.location = 'responder.php';</script>"; 
                exit;
              }

              for ($i=0; $i<mysqli_num_rows($result); $i++) {
                $descricao = mysql_result($result, $i, 'descricao');
                $resposta = mysql_result($result, $i, 'resposta');
                $correta = mysql_result($result, $i, 'correta');
                $idQuestao = mysql_result($result, $i, 'idQuestao');
                $a = mysql_result($result, $i, 'a');
                $b = mysql_result($result, $i, 'b');
                $c = mysql_result($result, $i, 'c');
                $d = mysql_result($result, $i, 'd');
                $e = mysql_result($result, $i, 'e');
                $f = mysql_result($result, $i, 'f');
                $g = mysql_result($result, $i, 'g');
                $h = mysql_result($result, $i, 'h');

                // echo "<div class='pergunta'>
                //         <br>
                //         <p><b>".($i+1).")</b> &nbsp; $descricao</p>
                //         <p class='".($correta==0 ? 'green' : ($resposta==0 ? 'red' : ''))."'><b>a) &nbsp; </b> $a</p>
                //         <p class='".($correta==1 ? 'green' : ($resposta==1 ? 'red' : ''))."'><b>b) &nbsp; </b> $b</p>";
                // echo $c == "" ? "" : "<p class='".($correta==2 ? 'green' : ($resposta==2 ? 'red' : ''))."'><b>c) &nbsp; </b> $c</p>";
                // echo $d == "" ? "" : "<p class='".($correta==3 ? 'green' : ($resposta==3 ? 'red' : ''))."'><b>d) &nbsp; </b> $d</p>";
                // echo $e == "" ? "" : "<p class='".($correta==4 ? 'green' : ($resposta==4 ? 'red' : ''))."'><b>e) &nbsp; </b> $e</p>";
                // echo $f == "" ? "" : "<p class='".($correta==5 ? 'green' : ($resposta==5 ? 'red' : ''))."'><b>f) &nbsp; </b> $f</p>";
                // echo $g == "" ? "" : "<p class='".($correta==6 ? 'green' : ($resposta==6 ? 'red' : ''))."'><b>g) &nbsp; </b> $g</p>";
                // echo $h == "" ? "" : "<p class='".($correta==7 ? 'green' : ($resposta==7 ? 'red' : ''))."'><b>h) &nbsp; </b> $h</p>";
                // echo "</div>";
                
                echo "<div class='pergunta'><br>";
                echo "<p><b>".($i+1).") <span style='color: red'>#$idQuestao</span></b> &nbsp; $descricao</p>";
                echo "<p class='". ($resposta==0 ? 'red' : '') ." ". ($correta==0 ? 'green' : '') ." '><b>a) &nbsp; </b> $a</p>";
                echo "<p class='". ($resposta==1 ? 'red' : '') ." ". ($correta==1 ? 'green' : '') ." '><b>b) &nbsp; </b> $b</p>";
                
                echo $c == "" ? "" : "<p class='". ($resposta==2 ? 'red' : '') ." ". ($correta==2 ? 'green' : '') ." '><b>c) &nbsp; </b> $c</p>";
                echo $d == "" ? "" : "<p class='". ($resposta==3 ? 'red' : '') ." ". ($correta==3 ? 'green' : '') ." '><b>d) &nbsp; </b> $d</p>";
                echo $e == "" ? "" : "<p class='". ($resposta==4 ? 'red' : '') ." ". ($correta==4 ? 'green' : '') ." '><b>e) &nbsp; </b> $e</p>";
                echo $f == "" ? "" : "<p class='". ($resposta==5 ? 'red' : '') ." ". ($correta==5 ? 'green' : '') ." '><b>f) &nbsp; </b> $f</p>";
                echo $g == "" ? "" : "<p class='". ($resposta==6 ? 'red' : '') ." ". ($correta==6 ? 'green' : '') ." '><b>g) &nbsp; </b> $g</p>";
                echo $h == "" ? "" : "<p class='". ($resposta==7 ? 'red' : '') ." ". ($correta==7 ? 'green' : '') ." '><b>h) &nbsp; </b> $h</p>";
                echo "</div>";
              }
              echo "<br><hr>";

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