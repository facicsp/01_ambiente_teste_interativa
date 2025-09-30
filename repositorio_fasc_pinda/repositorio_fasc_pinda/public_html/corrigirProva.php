<?php 
  session_start(); 
  include 'LoginRestrito/conexao.php';
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title></title>

  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <link rel="stylesheet" href="css/cadastro.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


  <style>
  .hidden {
    display: none;
  }
  </style>

</head>

<body>
  <center>
    <?php
    if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
            include "topo.php";
            include './Util.php';
            $util = new Util();
            $seguranca = new Seguranca();

            echo "<br><h1>Corrigir prova</h1><br>";

            $idProfessor = $_SESSION['id'];
            $idAplicarProva = $seguranca->antisql($_GET['id']);
            $idProva = $seguranca->antisql($_GET['idProva']);

            $result = mysqli_query($conexao, "SELECT * FROM aplicarprova WHERE idProfessor = '$idProfessor' 
              AND idAplicarProva = '$idAplicarProva' AND idProva = '$idProva'");
            if (mysqli_num_rows($result) < 1) exit("Ops! Nenhum registro encontrado.");
          
            $result = mysqli_query($conexao, "SELECT idlistaresposta, resposta, correcao, descricao, peso, nome, ra FROM lista_resposta
              LEFT JOIN questao2 ON questao2.idQuestao = lista_resposta.idQuestao
              LEFT JOIN usuario ON usuario.idUsuario = lista_resposta.idaluno
              WHERE questao2.tipo = 'dissertativa' AND lista_resposta.idprova = '$idProva' ORDER BY nome ASC, descricao ASC");

            $linhas = mysqli_num_rows($result);
            
            if ($linhas > 0) {
                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                  <tr>
                    <td>Pergunta</td>
                    <td>RA</td>
                    <td>Nome</td>
                    <td>Resposta</td>
                    <td>Nota</td>
                  </tr>";

                for ($i=0; $i < $linhas; $i++) { 
                  $idlistaresposta = mysql_result($result, $i, "idlistaresposta");
                  $resposta = htmlspecialchars(mysql_result($result, $i, "resposta"));
                  $correcao = mysql_result($result, $i, "correcao");
                  $pergunta = htmlspecialchars(mysql_result($result, $i, "descricao"));
                  $peso     = mysql_result($result, $i, "peso");
                  $nome     = mysql_result($result, $i, "nome");
                  $ra       = mysql_result($result, $i, "ra");

                  //min='0' max='$peso' vai chegar uma hr que vamos tirar a senha do sistema pq o professor não sabem usar o sistema bando de animais
                  
                  echo "<tr>
                        <td>$pergunta</td>
                        <td>$ra</td>
                        <td>$nome</td>
                        <td>$resposta</td>
                        <td>
                          <form onsubmit='salvar(event)'>
                            <input type='hidden' value='$idlistaresposta' name='id'>
                            <input type='number' value='$correcao' name='nota' step='0.1' required>
                            <input type='submit' value='Gravar'>
                          </form>
                        </td>
                      </tr>";
                }

                echo "</table>";
            } else {
              echo "Ops! Nenhum registro encontrado.";
            }
      ?>
  </center>
</body>

<script>
function salvar(e) {
  e.preventDefault();
  var data = $(e.target).serialize();

  $.get('gravarCorrecaoProva.php?' + data, function(data, status) {
    var btn = $('input[type=submit]', e.target); 
    btn.val(data == 1? 'Gravado' : 'Erro');
    setTimeout(function() { btn.val('Gravar'); }, 3000);
  });
}
</script>

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