<?php
  session_start();
  include './conexao.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/cadastro.css">

    <style>
    label {
        color: #069;
    }

    .link {
        color: blue !important;
    }

    p {
        display: flex;
        align-items: center;
        text-align: left;
        color: #000;
        padding: 0 10px !important;
    }

    input[type="radio"] {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }

    .green {
        background-color: rgba(0, 128, 0, .2) !important;
    }

    .red {
        background-color: rgba(255, 0, 0, .2);
    }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <style>
    .hidden {
        display: none;
    }
    </style>

</head>

<body>
    <?php
    if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
            include "topo.php";
            include './Util.php';
            $util = new Util();
            $seguranca = new Seguranca();
            $idProfessor = $_SESSION['id'];
    ?>

    <div class='grid-80' style='margin-left: 10%;'>
        <div id="titulo">
            <h3>Visualizar Provas</h3>
        </div>

        <?php

        if (isset($_GET['id']) && $_GET['id'] != "") { //SELECIONOU UMA PROVA

            echo '<div class="voltar"><a href="visualizarProvas2.php"><img src="imagens/voltar.png">Voltar</a></div><br><br><br>';

            $idProva = $seguranca->antisql($_GET['id']);

            $result = mysql_query("SELECT * FROM questao2 WHERE idProva = '$idProva'");

            if (mysql_num_rows($result) == 0) exit("<script>window.location = 'visualizarProvas2.php?';</script>");

            for ($i=0; $i<mysql_num_rows($result); $i++) {
                $descricao = htmlspecialchars(mysql_result($result, $i, 'descricao'));
                $idQuestao = mysql_result($result, $i, 'idQuestao');
                $tipo = mysql_result($result, $i, 'tipo');
                $peso = mysql_result($result, $i, 'peso');

                echo "<div class='pergunta'><br>";
                echo "<p>Tipo: <b>$tipo</b> &nbsp; Peso: <b>$peso</b> &nbsp; <a class='link' href='editarQuestao.php?id=$idQuestao'>editar</a></p>";
                echo "<p><b>".($i+1).") <span style='color: red'>#$idQuestao</span></b> &nbsp; $descricao</p>";

                if ($tipo == 'objetiva') {
                    $resultAlt = mysql_query("SELECT * FROM alternativa WHERE idQuestao = '$idQuestao'");
                    for ($ialt=0; $ialt < mysql_num_rows($resultAlt); $ialt++) {
                        $alternativa = htmlspecialchars(mysql_result($resultAlt, $ialt, 'alternativa'));
                        $correta = mysql_result($resultAlt, $ialt, 'correta');

                        echo "<p class='". ($correta=='sim' ? 'green' : '') ." '><b>a) &nbsp; </b> $alternativa</p>";
                    }
                }

                echo "</div>";
              }



        } else { // VISUALIZAR LISTA DE PROVAS

            if ($_SESSION["tipo"] == "professor") $where = "WHERE prova.idProfessor = " . $_SESSION["id"];
            else $where = "";

            $result = mysql_query("SELECT prova.idProva, prova.titulo, aplicarprova.idAplicarProva FROM prova
              LEFT JOIN aplicarprova ON aplicarprova.idProva = prova.idProva $where");
            $linhas = mysql_num_rows($result);

            echo '<table border="0" align="center" id="consulta" cellpadding="5" cellspacing="0"><tr><td>Código</td><td>Título</td><td colspan="3">Operações</td></tr>';

            for ($i=0; $i<$linhas; $i++) {
                $idProva  = mysql_result($result, $i, "idProva");
                $titulo   = mysql_result($result, $i, "titulo");
                $aplicado = mysql_result($result, $i, "idAplicarProva");
                $titulo   = $titulo != "" ? $titulo : "Sem título";

                $resultQuestoes = mysql_query("SELECT COUNT(*) AS questoes FROM questao2 WHERE idProva = $idProva");
                if (mysql_result($resultQuestoes, 0, "questoes") > 0) {

                  $excluir = "<td>--</td>";
                  if ($aplicado == "") $excluir = "<td><a style='color:red;' href='excluirProva.php?id=$idProva'>Excluir</a></td>";
                    echo "<tr>
                    <td>$idProva</td>
                    <td>$titulo</td>
                    <td><a href='visualizarProvas2.php?id=$idProva'>Visualizar</a></td>
                    <td><a href='duplicarProva.php?id=$idProva'>Duplicar</a></td>
                    $excluir
                    </tr>";
                }

            }

            echo "</table>";
        }


        ?>
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
