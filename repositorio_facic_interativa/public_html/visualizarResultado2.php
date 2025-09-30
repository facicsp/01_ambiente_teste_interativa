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
              background-color: rgba(0, 128, 0, .2);
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
            <h2 id="nota"></h2>
            
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

              $result = mysqli_query($conexao, "SELECT * FROM lista_resposta 
                LEFT JOIN questao2 ON questao2.idQuestao = lista_resposta.idQuestao 
                WHERE lista_resposta.idprova = '$idProva' AND idaluno = '$idAluno'");


              if (mysqli_num_rows($result) == 0) {
                echo "<script>window.location = 'responder.php';</script>"; 
                exit;
              }

              for ($i=0; $i<mysqli_num_rows($result); $i++) {
                $descricao = htmlspecialchars(mysql_result($result, $i, 'descricao'));
                $resposta  = htmlspecialchars(mysql_result($result, $i, 'resposta' ));
                $correcao  = mysql_result($result, $i, 'correcao');
                $idQuestao = mysql_result($result, $i, 'idQuestao');
                $tipo      = mysql_result($result, $i, 'tipo');

                $retorno = is_numeric($correcao) ? ($correcao > 0 ? "acertou + $correcao" : "errou") : "aguardando correção";
                $nota += is_numeric($correcao) ? $correcao : 0;

                echo "<div class='pergunta'>
                        <br>
                        <p><b>".($i+1).") &nbsp; $descricao <span style='color: green'>($retorno)</span></b></p>";

                if ($tipo == "dissertativa") {
                  echo "<p><b>Resposta: &nbsp; </b> $resposta</p>";
                } else {
                  $resultAlt = mysqli_query($conexao, "SELECT * FROM alternativa WHERE idQuestao = '$idQuestao'");
                  for ($j=0; $j<mysqli_num_rows($resultAlt); $j++) { 
                    $idalternativa = mysql_result($resultAlt, $j, 'idalternativa');
                    $alternativa   = htmlspecialchars(mysql_result($resultAlt, $j, 'alternativa'));
                    $correta       = mysql_result($resultAlt, $j, 'correta');

                    echo "<p class='".($correta=='sim' ? 'green' : ($resposta==$idalternativa ? 'red' : ''))."'><b>a) &nbsp; </b> $alternativa</p>";
                  }
                }

                echo "</div>";
              }
              echo "<br><hr>";

              echo "<script>$('#nota').text('Nota: $nota');</script>";

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