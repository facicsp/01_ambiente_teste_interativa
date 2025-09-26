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
    .peso {
        margin: 10px 8px;
    }

    .checkbox {
        float: left;
        margin: 20px 10px;
    }

    .alternativa {
        width: calc(100% - 50px);
    }

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
            $idQuestao = $seguranca->antisql($_GET['id']);
          
          
          if (isset($_GET["operacao"])) {
           
          mysql_query("DELETE FROM questao2 WHERE idQuestao = '$idQuestao'");
          echo "<script>alert('Excluido com sucesso!'); location.href='visualizarProvas2.php';</script>";
            exit;
            
          } 
        
          if (isset($_POST["txtDescricao"])) {

            $id = $seguranca->antisql($_POST["txtId"]);
            $descricao = $seguranca->antisql($_POST["txtDescricao"]);
            $peso = $seguranca->antisql($_POST["txtPeso"]);
            $idAlternativa = $_POST["txtIdAlternativa"];
            $alternativa = $_POST["txtAlternativa"];
            $correta = $_POST["txtCorreta"];

            // , peso='$peso'
            mysql_query("UPDATE questao2 SET descricao='$descricao', peso='$peso' WHERE idQuestao = '$id'");

            for ($i=0; $i < sizeof($alternativa); $i++) { 
                $_alternativa = $seguranca->antisql($alternativa[$i]);
                $_idAlternativa = $seguranca->antisql($idAlternativa[$i]);
                $_correta     = $correta == $_idAlternativa ? "sim" : "nao";

                mysql_query("UPDATE `alternativa` SET `alternativa`='$_alternativa',`correta`='$_correta' WHERE idalternativa = '$_idAlternativa'");
            }


            echo "<script>location.href='editarQuestao.php?id=$idQuestao';</script>";
            exit;
          }
    ?>

    <div class='grid-80' style='margin-left: 10%;'>
        <div id="titulo">
            <h3>Visualizar Provas</h3>
        </div>

        <?php
        
        
            echo '<div class="voltar"><a href="visualizarProvas2.php"><img src="imagens/voltar.png">Voltar</a></div><br><br><br>';
            
            $result = mysql_query("SELECT * FROM questao2 WHERE idQuestao = $idQuestao");

            $descricao = mysql_result($result, $i, 'descricao');
            $idQuestao = mysql_result($result, $i, 'idQuestao');
            $tipo = mysql_result($result, $i, 'tipo');
            $peso = mysql_result($result, $i, 'peso');
            
            echo "<div class='pergunta'>";
            echo "<form action='editarQuestao.php?id=$idQuestao' method='post'>";
            echo "<p class='peso'>Peso: <input name='txtPeso' value='$peso' type='number' min='0' max='10' step='0.1'></p>";
            echo "<input name='txtId' value='$idQuestao' type='hidden'>";
            echo "<textarea name='txtDescricao'>$descricao</textarea>";

            if ($tipo == 'objetiva') {
                $resultAlt = mysql_query("SELECT * FROM alternativa WHERE idQuestao = '$idQuestao'");
                for ($ialt=0; $ialt < mysql_num_rows($resultAlt); $ialt++) {
                    $idAlternativa = mysql_result($resultAlt, $ialt, 'idalternativa');
                    $alternativa = mysql_result($resultAlt, $ialt, 'alternativa');
                    $correta = mysql_result($resultAlt, $ialt, 'correta');

                    echo "<input name='txtIdAlternativa[]' value='$idAlternativa' type='hidden'>";
                    echo "<input class='checkbox' ".($correta=='sim' ? 'checked' : '')." type='radio' value='$idAlternativa' name='txtCorreta'>";
                    echo "<textarea class='alternativa' name='txtAlternativa[]'>$alternativa</textarea>";
                }
            }

            echo "</div><button class='botao' type='submit'>Salvar</button></form>";
              
            
        
        ?>
      
      <a href="editarQuestao.php?id=<?=$idQuestao?>&operacao=excluir" class='botao' style="background-color: red;">Excluir</a>
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