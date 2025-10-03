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
    label {
        color: #069;
    }

    p {
        display: flex;
        align-items: center;
        text-align: left;
        color: #000;
        padding: 0 10px !important;
    }

    input[type="radio"] {
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
        transform: scale(1.3);
        /* Standard Property */
        transform-origin: 0 0;
        /* Standard Property */
    }

    textarea {
        color: #000 !important;
    }
    </style>
</head>

<body>
    <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "professor") {
                include "topo.php";
                
                ?>


    <div class="dados">
        <div class="barratitulo">
            <h1>Questionário</h1>
        </div>

        <?php

function getLetra($numero){
    switch ($numero) {
      case 0:
        $escolha = "a";
        break;
      case 1:
        $escolha = "b";
        break;
      case 2:
        $escolha = "c";
        break;
      case 3:
        $escolha = "d";
        break;
      case 4:
        $escolha = "e";
        break;
      case 5:
        $escolha = "f";
        break;
      case 6:
        $escolha = "g";
        break;
      case 7:
        $escolha = "h";
        break;
    }
  
    return $escolha;
  }

            include "conexao.php";

            $seguranca = new Seguranca();
            $idProva = $seguranca->antisql($_GET["idProva"]);
            
            $result = mysql_query("SELECT nome, ra, SUM(IF(correcao > 0, 1, 0)) AS acertos, COUNT(*) AS erros FROM lista_resposta 
                    LEFT JOIN questao2 ON questao2.idQuestao = lista_resposta.idQuestao 
                    LEFT JOIN usuario ON usuario.idUsuario = lista_resposta.idAluno 
                    WHERE lista_resposta.idProva = '$idProva' AND questao2.tipo = 'objetiva'
                    GROUP BY idAluno");
            $linhas = mysql_num_rows($result);
            
            echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0' width='950px' align='center'>
                <tr>
                    <td>RA</td>
                    <td>Aluno</td>
                    <td>Acertos</td>
                    <td>Erros</td>
                </tr>";

            for ($i = 0; $i < $linhas; $i++) {
                
                $ra      = mysql_result($result, $i, "ra");
                $nome    = mysql_result($result, $i, "nome");
                $acertos = mysql_result($result, $i, "acertos");
                $erros   = mysql_result($result, $i, "erros");
                //$erros   = $erros - $acertos;
                
                echo "<tr>
                    <td>$ra</td>
                    <td>$nome</td>
                    <td>$acertos</td>
                    <td>".($erros - $acertos)."</td>
                </tr>";

            }

                echo "</table>";

            echo "<br><hr>";

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