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

            // Buscar informações da prova
            $resultProva = mysql_query("SELECT * FROM prova WHERE idProva = '$idProva'");
            if (mysql_num_rows($resultProva) == 0) exit("<script>alert('Prova não encontrada!'); window.location = 'visualizarProvas2.php';</script>");

            $tituloProva = mysql_result($resultProva, 0, 'titulo');
            $idProfessorProva = mysql_result($resultProva, 0, 'idProfessor');

            // Processar atualização do título (se foi submetido)
            if (isset($_POST['atualizar_titulo']) && isset($_POST['novo_titulo'])) {
                $novoTitulo = $seguranca->antisql($_POST['novo_titulo']);

                // Verificar permissão
                if ($idProfessorProva == $_SESSION['id'] || $_SESSION["tipo"] == "administrador") {
                    $queryUpdate = "UPDATE prova SET titulo = '$novoTitulo' WHERE idProva = '$idProva'";
                    if (mysql_query($queryUpdate)) {
                        $tituloProva = $novoTitulo;
                        echo "<script>
                            alert('Título atualizado com sucesso!');
                            window.location = 'visualizarProvas2.php?id=$idProva';
                        </script>";
                    } else {
                        echo "<div style='background:#f8d7da;padding:15px;margin:10px 0;border-radius:5px;color:#721c24;'>
                            <strong>❌ Erro ao atualizar título:</strong> " . mysql_error() . "
                        </div>";
                    }
                } else {
                    echo "<div style='background:#fff3cd;padding:15px;margin:10px 0;border-radius:5px;color:#856404;'>
                        <strong>⚠️ Aviso:</strong> Você não tem permissão para editar esta prova.
                    </div>";
                }
            }

            // Formulário de edição do título
            echo "<div style='background:#f8f9fa;padding:20px;margin:20px 0;border-radius:8px;border:2px solid #dee2e6;'>
                <form method='post' action='visualizarProvas2.php?id=$idProva' style='display:flex;align-items:center;gap:10px;'>
                    <label style='font-weight:bold;color:#495057;margin:0;'>Título da Prova:</label>
                    <input type='text' name='novo_titulo' value='" . htmlspecialchars($tituloProva) . "'
                           style='flex:1;padding:10px;border:2px solid #ced4da;border-radius:5px;font-size:16px;'
                           required>
                    <button type='submit' name='atualizar_titulo'
                            style='background:#28a745;color:white;border:none;padding:10px 20px;border-radius:5px;font-weight:bold;cursor:pointer;font-size:14px;'>
                        💾 SALVAR
                    </button>
                </form>
                <small style='color:#6c757d;display:block;margin-top:8px;'>
                    <strong>ID da Prova:</strong> #$idProva &nbsp;|&nbsp;
                    <strong>Professor:</strong> " . ($idProfessorProva == $_SESSION['id'] ? 'Você' : "ID $idProfessorProva") . "
                </small>
            </div>";

            $result = mysql_query("SELECT * FROM questao2 WHERE idProva = '$idProva'");

            if (mysql_num_rows($result) == 0) {
                echo "<div style='background:#fff3cd;padding:20px;margin:20px 0;border-radius:8px;color:#856404;text-align:center;'>
                    <strong>⚠️ Esta prova não possui questões cadastradas.</strong><br>
                    <a href='cadastroProva2.php' style='color:#004085;'>Clique aqui para adicionar questões</a>
                </div>";
                echo "</div></body></html>";
                exit;
            }

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
