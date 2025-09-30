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
  input[type="radio"] {
    width: 100px;
  }

  label {
    color: #069;
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
    <div class="barratitulo">
      <h1>Questionário</h1>
    </div>

    <?php
                        include "LoginRestrito/conexao.php";
                        $sql = "SELECT * FROM aplicarprova WHERE '".date("Y-m-d", time())." 23:59:59' >= abertura AND idProfessor != '' AND idDisciplina = '$idDisciplina' AND bimestre NOT IN (10,20,30,40)";
                        $resultados = mysqli_query($conexao, $sql);
                        $linhas = mysqli_num_rows($resultados);

                        if ($linhas > 0) {
                            for ($i = 0; $i < $linhas; $i++) {

                                $idApl  = mysql_result($resultados, $i, "idAplicarProva");
                                $titulo = mysql_result($resultados, $i, "titulo");
                                $data   = mysql_result($resultados, $i, "fechamento");

                                $result = mysqli_query($conexao, "SELECT idAluno FROM respostas 
                                    LEFT JOIN questao ON questao.idQuestao = respostas.idQuestao 
                                    LEFT JOIN aplicarprova ON aplicarprova.idProva = questao.idProva 
                                    WHERE respostas.idAluno = '$idAluno' AND aplicarprova.idAplicarProva = '$idApl'");

                                $status = "Responder";
                                $action = "responderQuestoes.php";

                                if (mysqli_num_rows($result) > 0) {
                                  if (strtotime(date("Y-m-d")) > strtotime($data)) {
                                    $status = "Ver resultado";
                                    $action = "visualizarResultado2.php";
                                  } else {
                                    $status = "Aguardando resultado";
                                    $action = "#"; $idApl = 0;
                                  }
                                } else if (strtotime(date("Y-m-d")) > strtotime($data)) {
                                  $status = "Prazo excedido";
                                  $action = "#"; $idApl = 0;
                                }

                                echo "<div class='pergunta'><br><h2>Pergunta $titulo</h2>";
                                echo "<h3>Disponível até: ".date_format(date_create($data),"d/m/Y")."</h3>";

                                echo "<form method='post' action='".($status == "Responder" ? 'responderQuestoes.php' : 'visualizarResultado.php')."'>";
                                echo "<input type='hidden' name='id' value='$idApl'>";
                                echo "<input type='submit' value='$status' ".($status == "Ver resultado"|| $status == "Aguardando resultado" ? 'style="background-color: green !important;"' : '')." ".($status == "Prazo excedido" ? 'style="background-color: red !important;" disabled' : '').">";
                                echo "</form><br><br></div>";
                                
                            }
                        }


                        // PROVAS P1 e P2
                        $sql = "SELECT * FROM aplicarprova WHERE '".date("Y-m-d", time())." 23:59:59' >= abertura AND idProfessor != '' AND idDisciplina = '$idDisciplina' AND bimestre IN (10,20,30,40)";
                        $resultados = mysqli_query($conexao, $sql);
                        $linhas = mysqli_num_rows($resultados);

                        if ($linhas > 0) {
                            for ($i = 0; $i < $linhas; $i++) {

                                $idApl  = mysql_result($resultados, $i, "idAplicarProva");
                                $idProva= mysql_result($resultados, $i, "idProva");
                                $titulo = mysql_result($resultados, $i, "titulo");
                                $data   = mysql_result($resultados, $i, "fechamento");
                                $bim    = mysql_result($resultados, $i, "bimestre");

                                $result = mysqli_query($conexao, "SELECT idaluno FROM lista_resposta 
                                  WHERE idaluno = '$idAluno' AND idprova = '$idProva'");

                                $status = "Responder";
                                $action = "responderQuestoes2.php";

                                if (mysqli_num_rows($result) > 0) {
                                  if (strtotime(date("Y-m-d")) > strtotime($data)) {
                                    $status = "Ver resultado";
                                    $action = "visualizarResultado2.php";
                                  } else {
                                    $status = "Aguardando resultado";
                                    $action = "#"; $idApl = 0;
                                  }
                                } else if (strtotime(date("Y-m-d")) > strtotime($data)) {
                                  $status = "Prazo excedido";
                                  $action = "#"; $idApl = 0;
                                }

                                echo "<div class='pergunta'><br><h2>Pergunta $titulo</h2>";
                                echo "<h3>Disponível até: ".date_format(date_create($data),"d/m/Y")."</h3>";

                                echo "<form method='post' action='$action'>";
                                echo "<input type='hidden' name='id' value='$idApl'>";
                                echo "<input type='submit' value='$status' ".($status == "Ver resultado" || $status == "Aguardando resultado" ? 'style="background-color: green !important;"' : '')." ".($status == "Prazo excedido" ? 'style="background-color: red !important;" disabled' : '').">";
                                echo "</form><br><br></div>";
                                
                            }
                        }
                        // fim p1p2



                        ?>

    </table>
    <hr>
    <div class="voltar"><a href="index.php"><i class="icon small rounded color1 fa-arrow-left"></i> Voltar</a></div>

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