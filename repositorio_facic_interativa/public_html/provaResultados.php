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
    
        <div id="titulo">
          <h3>Resultados questionário</h3>
        </div>
        <center>
          <br><br>
          <?php

            $seguranca = new Seguranca();
            $idAplicar = $seguranca->antisql($_GET["id"]);
            $idProfessor = $_SESSION["id"];
            
            $sql = "SELECT * FROM aplicarprova WHERE idProfessor = '$idProfessor' AND idAplicarProva = '$idAplicar'";
            $resultados = mysqli_query($conexao, $sql);
            $linhas = mysqli_num_rows($resultados);
            
            
            if ($linhas > 0) {
                
                $idProva = mysql_result($resultados, 0, 'idProva');
                $idDisciplina = mysql_result($resultados, 0, 'idDisciplina');
                $idTurma = mysql_result($resultados, 0, 'idTurma');
                
                $result = mysqli_query($conexao, "SELECT nome, ra, SUM(IF(questao.correta = respostas.resposta, 1, 0)) AS acertos FROM respostas 
                        LEFT JOIN questao ON questao.idQuestao = respostas.idQuestao 
                        LEFT JOIN usuario ON usuario.idUsuario = respostas.idAluno 
                        WHERE idProva = '$idProva' 
                        GROUP BY idAluno
                        ORDER BY nome ASC");
                $linhas = mysqli_num_rows($result);
                
                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0' width='950px' align='center'>
                  <tr>
                      <td>RA</td>
                      <td>Aluno</td>
                      <td>Acertos</td>
                      <td>Erros</td>
                  </tr>";

                for ($i = 0; $i < $linhas; $i++) {
                    
                    $ra = mysql_result($result, $i, "ra");
                    $nome = mysql_result($result, $i, "nome");
                    $acertos = mysql_result($result, $i, "acertos");
                    $erros = ($acertos - 10) * -1;
                    
                    echo "<tr>
                      <td>$ra</td>
                      <td>$nome</td>
                      <td>$acertos</td>
                      <td>$erros</td>
                  </tr>";

                  }

                  echo "</table>";
              } else {
                  echo "Nenhuma registro encontrado.";
              }
              ?>

      </td>
    </tr>

  </table>
  </center>

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