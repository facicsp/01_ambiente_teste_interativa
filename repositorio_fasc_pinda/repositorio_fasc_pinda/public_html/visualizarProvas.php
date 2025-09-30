<?php 
  session_start(); 
  include 'LoginRestrito/conexao.php';
  
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
  function isAvaliativo(el) {
    if (el.value == 1) {
      $('.isFrequencia').addClass('hidden')
      $('.isAvaliativo').removeClass('hidden')
      $('#idAplicar').removeAttr('required')
      $('#dataAtividade').addAttr('required')
    } else {
      $('.isAvaliativo').addClass('hidden')
      $('.isFrequencia').removeClass('hidden')
      $('#idAplicar').addAttr('required')
      $('#dataAtividade').removeAttr('required')
    }
  }
  </script>

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
        
            echo '<div class="voltar"><a href="visualizarProvas.php"><img src="imagens/voltar.png">Voltar</a></div><br><br><br>';
            
            $idProva = $seguranca->antisql($_GET['id']);
            
            $result = mysqli_query($conexao, "SELECT * FROM questao WHERE idProva = '$idProva'");
            
            if (mysqli_num_rows($result) == 0) {
                echo "<script>window.location = 'visualizarProvas.php?$idProva';</script>"; 
                exit;
            }
            
            for ($i=0; $i<mysqli_num_rows($result); $i++) {
                $descricao = mysql_result($result, $i, 'descricao');
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
                
                echo "<div class='pergunta'><br>";
                echo "<p><b>".($i+1).") <span style='color: red'>#$idQuestao</span></b> &nbsp; $descricao</p>";
                echo "<p class='". ($correta==0 ? 'green' : '') ." '><b>a) &nbsp; </b> $a</p>";
                echo "<p class='". ($correta==1 ? 'green' : '') ." '><b>b) &nbsp; </b> $b</p>";
                
                echo $c == "" ? "" : "<p class='". ($correta==2 ? 'green' : '') ." '><b>c) &nbsp; </b> $c</p>";
                echo $d == "" ? "" : "<p class='". ($correta==3 ? 'green' : '') ." '><b>d) &nbsp; </b> $d</p>";
                echo $e == "" ? "" : "<p class='". ($correta==4 ? 'green' : '') ." '><b>e) &nbsp; </b> $e</p>";
                echo $f == "" ? "" : "<p class='". ($correta==5 ? 'green' : '') ." '><b>f) &nbsp; </b> $f</p>";
                echo $g == "" ? "" : "<p class='". ($correta==6 ? 'green' : '') ." '><b>g) &nbsp; </b> $g</p>";
                echo $h == "" ? "" : "<p class='". ($correta==7 ? 'green' : '') ." '><b>h) &nbsp; </b> $h</p>";
                echo "</div>";
              }
              
              
            
        } else { // VISUALIZAR LISTA DE PROVAS
        
            if ($_SESSION["tipo"] == "professor") $where = "WHERE idProfessor = " . $_SESSION["id"];
            else $where = "";
            
            
            $result = mysqli_query($conexao, "SELECT * FROM prova $where");
            $linhas = mysqli_num_rows($result);
            
            echo '<table border="0" align="center" id="consulta" cellpadding="5" cellspacing="0"><tr><td>Código</td><td>Título</td><td>Operações</td></tr>';
    
            for ($i=0; $i<$linhas; $i++) { 
                $idProva = mysql_result($result, $i, "idProva");
                $titulo  = mysql_result($result, $i, "titulo");
                $titulo  = $titulo != "" ? $titulo : "Sem título";
    
                $resultQuestoes = mysqli_query($conexao, "SELECT COUNT(*) AS questoes FROM questao WHERE idProva = $idProva");
                
                if (mysql_result($resultQuestoes, 0, "questoes") > 9) {
                    echo "<tr>
                        <td>$idProva</td>
                        <td>$titulo</td>
                        <td><a href='visualizarProvas.php?id=$idProva'>Visualizar</a></td>
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