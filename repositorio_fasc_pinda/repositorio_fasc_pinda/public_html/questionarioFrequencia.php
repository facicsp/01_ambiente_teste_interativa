<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <title></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="css/cadastro.css">
</head>

<body>
  <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador") {
                include "topo.php";
                include "LoginRestrito/conexao.php";
                $seguranca = new Seguranca();

                if (isset($_POST["id"])) {
                  $idDisciplina = $seguranca->antisql($_POST["id"]);
                  $_SESSION["disciplinaFreq"] = $idDisciplina;                  
                } else {
                  $idDisciplina = $_SESSION["disciplinaFreq"];
                }

                $result = mysqli_query($conexao, "SELECT disciplina, idTurma FROM disciplina WHERE idDisciplina = '$idDisciplina'");

                if (mysqli_num_rows($result)>0) {
                  $idTurma = mysql_result($result, 0, "idTurma");
                  $nomeDisciplina = mysql_result($result, 0, "disciplina");
                } else exit("Ops! Houve algum erro");

                // INSERT
                if (isset($_POST["acao"]) && $_POST["acao"] == "enviado") {
                  $abertura   = $seguranca->antisql($_POST["txtAbertura"]);
                  $fechamento = $seguranca->antisql($_POST["txtFechamento"]);

                  mysqli_query($conexao, "INSERT INTO aplicarprova (abertura, fechamento, idDisciplina, idTurma, bimestre) VALUES
                                ('$abertura', '$fechamento', '$idDisciplina', '$idTurma', '0')");
                                
                  echo "<script>alert('Gravação realizada com sucesso!');</script>";
                }

                // UPDATE
                if (isset($_POST["operacao"]) && $_POST["operacao"] == "update") {
                  $abertura       = $seguranca->antisql($_POST["txtAbertura"]);
                  $fechamento     = $seguranca->antisql($_POST["txtFechamento"]);
                  $idAplicarProva = $seguranca->antisql($_POST["txtId"]);

                  mysqli_query($conexao, "UPDATE aplicarprova SET abertura = '$abertura', fechamento = '$fechamento' 
                                WHERE idAplicarProva = '$idAplicarProva'"); 

                  echo "<script>alert('Gravação realizada com sucesso!');</script>";
                }

                // DELETE
                if (isset($_POST["operacao"]) && $_POST["operacao"] == "delete") {
                  $idAplicarProva = $seguranca->antisql($_POST["txtId"]);

                  mysqli_query($conexao, "DELETE FROM aplicarprova WHERE idAplicarProva = '$idAplicarProva'"); 
                                                               
                  echo "<script>alert('Gravação realizada com sucesso!');</script>";
                }
                  
              ?>


  <table width="950px" align="center" id="tabelaprincipal">
    <tr>
      <td>
        <div id="titulo">
          <h3>Questionário de Frequência</h3>
          <h3><?= $nomeDisciplina ?></h3>
        </div>
        <form method="post" action="#">
          <div align="center">
            <table id="cadastro">
              <tr>
                <td style="width: 40%">
                  Abertura do questionário
                </td>
                <td>
                  <input type='date' name='txtAbertura' required>
                </td>
              </tr>
              <tr>
                <td style="width: 40%">
                  Fechamento do questionário
                </td>
                <td>
                  <input type='date' name='txtFechamento' required>
                  <br><br>
                  <input type="hidden" name="acao" value="enviado">
                  <input type='submit' value='Gravar'>
                </td>
              </tr>
            </table>
          </div>
        </form>
        <hr>
        <center>
          <h3>Consultar questionário de Frequência</h3>
        </center>

        <?php
            $result = mysqli_query($conexao, "SELECT * FROM aplicarprova WHERE idDisciplina = '$idDisciplina' AND idTurma = '$idTurma' AND bimestre = 0");
            $linhas = mysqli_num_rows($result);

            if ($linhas > 0) {
              echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
              <tr>
                <td>Código</td>
                <td>Abertura</td>
                <td>Fechamento</td>
                <td colspan='2'>Operações</td>
              </tr>";

                for ($i = 0; $i < $linhas; $i++) {
                    $idAplicarProva = mysql_result($result, $i, "idAplicarProva");
                    $abertura = mysql_result($result, $i, "abertura");
                    $fechamento = mysql_result($result, $i, "fechamento");
                    $idProfessor = mysql_result($result, $i, "idProfessor");
                  
                    echo "<form method='post' action='#'>
                    <tr>
                      <td>$idAplicarProva</td>
                      <td>
                        <input type='date' name='txtAbertura' value='".date_format(date_create($abertura), "Y-m-d")."' required>
                      </td>
                      <td>
                        <input type='date' name='txtFechamento' value='".date_format(date_create($fechamento), "Y-m-d")."' required>
                      </td>";
                  
                    echo "
                    <input type='hidden' name='operacao' value='update'>
                    <input type='hidden' name='txtId' value='$idAplicarProva'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>";

                      if ($idProfessor == "") {
                        echo "<form method='post' action='#'>
                        <input type='hidden' name='operacao' value='delete'>
                        <input type='hidden' name='txtId' value='$idAplicarProva'>
                        <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                        </form>";
                      } else {
                        echo "<td title='Não pode ser excluído'>O professor já aplicou esse questionário</td>";
                      }
                      
                      echo "</tr>";

                  }

                    echo "</table>";
                } else {
                    echo "Nenhuma registro encontrado.";
                }
                ?>
      </td>
    </tr>

  </table>

</body>

</html>
<?php } else {
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