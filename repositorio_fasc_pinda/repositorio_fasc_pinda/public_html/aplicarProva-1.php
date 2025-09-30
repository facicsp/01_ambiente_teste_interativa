<?php 
  session_start(); 
  include 'LoginRestrito/conexao.php';
  
  if (isset($_GET["turma"])) {
    $idDisciplina = $_GET["disciplina"];
    $idTurma = $_GET["turma"];

    $result = mysqli_query($conexao, "SELECT * FROM aplicarprova WHERE idDisciplina = '$idDisciplina' AND idTurma = '$idTurma' AND bimestre = 0");
    $linhas = mysqli_num_rows($result);
    if ($linhas > 0) {
      echo "<option value='' selected disabled>::Escolha uma data::</option>";
      for ($i = 0; $i < $linhas; $i++) {
          $idAplicarProva = mysql_result($result, $i, "idAplicarProva");
          $abertura = mysql_result($result, $i, "abertura");
          $fechamento = mysql_result($result, $i, "fechamento");
          $idProva = mysql_result($result, $i, "idProva");

          $disabled = $idProva == NULL ? "" : "disabled";
          $mensagem = $idProva == NULL ? "" : "JÁ APLICADO";

          echo "<option value='$idAplicarProva' $disabled>Disponível de ".date_format(date_create($abertura), "d/m/Y")." até ".date_format(date_create($fechamento), "d/m/Y")." $mensagem</option>";
      }
    } else {
        echo "<option value='' selected disabled>::A secretária não estipulou nenhum questionário de frequência para essa disciplina::</option>";
    }
    
    exit;
  }  
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title></title>
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  
  <link rel="stylesheet" href="css/cadastro.css">

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


  <table width="950px" align="center" id="tabelaprincipal">
    <tr>
      <td>
        <div id="titulo">
          <h3>Aplicar questionário</h3>
        </div>
        <form method="post" action="gravarAplicarProva.php">
          <div align="center">
            <table id="cadastro">
              <tr>
                <td>
                  Título
                </td>
                <td>
                  <input type="text" name="txtDescricao" required>
                </td>
              </tr>
              <tr>
                <td>Questionário</td>
                <td style="display: flex">
                  <select name="txtProva" required>
                    <option value="" selected disabled>::Escolha um questionário que ainda não foi aplicado::</option>
                    <?php
                        $result = mysqli_query($conexao, "SELECT * FROM prova WHERE idProfessor = " . $_SESSION["id"]);
                        $linhas = mysqli_num_rows($result);

                        for ($i=0; $i<$linhas; $i++) { 
                            $idProva = mysql_result($result, $i, "idProva");
                            $titulo  = mysql_result($result, $i, "titulo");

                            $resultQuestoes = mysqli_query($conexao, "SELECT COUNT(*) AS questoes FROM questao WHERE idProva = $idProva");
                            
                            if (mysql_result($resultQuestoes, 0, "questoes") > 0) {
                              $resultAplicado = mysqli_query($conexao, "SELECT idProva FROM aplicarprova WHERE idProva = $idProva");

                              if (mysqli_num_rows($resultAplicado) == 0) 
                                echo "<option value='$idProva'>$titulo</option>";
                            }
                        }
                    ?>
                  </select>
                  <a href='cadastroProva.php' class="botao" style="
                    text-align: center;
                    color: #FFF;
                    margin-left: 10px;
                    border: none;
                    border-radius: 50px;
                    background: green;
                    font-weight: normal;">Novo</a>
                    <a href='visualizarProvas.php' class="botao" style="
                    text-align: center;
                    color: #FFF;
                    margin-left: 10px;
                    border: none;
                    border-radius: 50px;
                    background: orange;
                    font-weight: normal;">visualizar</a>
                </td>
              </tr>
              <tr>
                <td>Tipo</td>
                <td>
                  <select name="txtAvaliativo" onchange="isAvaliativo(this)">
                    <option value="1">Avaliativo</option>
                    <option value="0" selected>Frequência</option>
                  </select>
                </td>
              </tr>
              <tr class="isAvaliativo hidden">
                <td>Bimestre</td>
                <td>
                  <select name="txtBimestre">
                    <option value="1">1° Bimestre (Isso irá compor a nota "A. Virtual l")</option>
                    <option value="2">2° Bimestre (Isso irá compor a nota "A. Virtual ll")</option>
                  </select>
                </td>
              </tr>
              <tr class="isAvaliativo hidden">
                <td>Data de Entrega da Atividade</td>
                <td>
                  <input type="date" name="txtDataAtividade" id="dataAtividade">
                </td>
              </tr>
              <tr>
                <td>
                  Turma
                </td>
                <td>
                  <select name="txtTurma" required onchange="setTurma(this)">
                    <option value="" selected disabled>::Escolha uma turma::</option>
                    <?php
                        if ($_SESSION["tipo"] == "professor") {
                            $idProfessor = $_SESSION["id"];
                            $sql = "SELECT idturma, turma, semestre FROM turma 
                              WHERE semestre = '{$_SESSION["semestre"]}' AND 
                              idturma in (select disciplina.idTurma from disciplina where idprofessor = '$idProfessor')";
                        } else {
                            $sql = "SELECT * FROM turma WHERE turma.semestre = '{$_SESSION["semestre"]}' ORDER BY turma";
                        }
                        $resultados = mysqli_query($conexao, $sql);
                        $linhas = mysqli_num_rows($resultados);
                        if ($linhas > 0) {
                            for ($i = 0; $i < $linhas; $i++) {
                                $idturma = mysql_result($resultados, $i, "idturma");
                                $turma = mysql_result($resultados, $i, "turma");
                                echo "<option value='$idturma'>$turma</option>";
                            }
                        }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                  Disciplina
                </td>
                <td>
                  <select name="txtDisciplina" required onchange="setDisciplina(this)">
                    <option value="" selected disabled>::Escolha uma disciplina::</option>
                    <?php
                        if ($_SESSION["tipo"] == "professor") {
                            $idProfessor = $_SESSION["id"];
                            $sql = "SELECT d.*, t.turma FROM disciplina d,turma t 
                              WHERE d.semestre = '{$_SESSION["semestre"]}' 
                              AND d.idTurma = t.idTurma AND d.idProfessor = '$idProfessor' 
                              ORDER BY d.disciplina";
                        } else {
                            $sql = "SELECT d.*,t.turma FROM disciplina d,turma t 
                              WHERE d.semestre = '{$_SESSION["semestre"]}' 
                              AND d.idTurma = t.idTurma ORDER BY d.disciplina";
                        }
                        $resultados = mysqli_query($conexao, $sql);
                        $linhas = mysqli_num_rows($resultados);
                        if ($linhas > 0) {
                            for ($i = 0; $i < $linhas; $i++) {
                                $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                                $disciplina = mysql_result($resultados, $i, "disciplina");
                                $turma = mysql_result($resultados, $i, "turma");
                                if ($_SESSION["tipo"] == "professor") {
                                    if ($i == 0) $listaDisciplina .= $idDisciplina;
                                    else $listaDisciplina .= "," . $idDisciplina;
                                }
                                echo "<option value='$idDisciplina'>$disciplina - $turma</option>";
                            }
                        }
                    ?>
                  </select>
                </td>
              </tr>
              <tr class="isFrequencia">
                <td>Frequência</td>
                <td>
                  <select name="txtIdAplicar" id="idAplicar">
                    <option value="" selected disabled>::A secretária não estipulou nenhum questionário de frequência::</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                  <input type='submit' value='Gravar'>
                </td>
              </tr>
            </table>
          </div>
        </form>
        <hr>
        <center>
          <div class="consulta grid-100">
            <form method="post" action="#">
              <b>Consultar aplicação de questionários</b><input type="text" name="txtConsulta">
              <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
            </form>
          </div>
          <br><br>
          <?php

            $seguranca = new Seguranca();

            if (isset($_POST['operacao']) && $_POST['operacao'] == 'excluir') {
              $idAplicarProva = $seguranca->antisql($_POST['id']);
              mysqli_query($conexao, "DELETE FROM aplicarprova WHERE idAplicarProva = '$idAplicarProva' AND idProfessor = '$idProfessor'");
              echo "<script>alert('Exclusão realizada com sucesso!');</script>";       
            }
          
            $titulo     = isset($_POST['txtConsulta']) ? $seguranca->antisql($_POST['txtConsulta']) : '';
            $resultados = mysqli_query($conexao, "SELECT * FROM aplicarprova WHERE idProfessor = '$idProfessor' AND titulo LIKE '%$titulo%' AND bimestre IN (0,1,2)");
            $linhas     = mysqli_num_rows($resultados);
            
            if ($linhas > 0) {
                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                  <tr>
                  <td>Título</td>
                  <td>Data de entrega</td>
                  <td>Tipo</td>
                  <td colspan='2'>Operações</td>
                  <td>Resultados</td>
                  </tr>";

                for ($i = 0; $i < $linhas; $i++) {
                    $id = mysql_result($resultados, $i, "idAplicarProva");
                    $titulo = mysql_result($resultados, $i, "titulo");
                    $data = mysql_result($resultados, $i, "fechamento");
                    $bimestre = mysql_result($resultados, $i, "bimestre");

                    if ($bimestre == 0) $bimestre = 'Frequência';
                    else if ($bimestre == 1) $bimestre = 'Bimestre 1';
                    else $bimestre = 'Bimestre 2';

                    echo "<form method='post' action='alterarAplicarProva.php'>
                    <tr><td style='width:40%;'><input type='text' value='$titulo' name='txtTitulo'></td>
                    <td><input type='date' value='".date_format(date_create($data), "Y-m-d")."' name='txtData' ".($bimestre == 'Frequência' ? 'readonly title="Esta data foi definida pela secretaria"' : '')."></td>
                    <td><input type='text' value='$bimestre' readonly></td>";
                      
                      echo "<input type='hidden' name='id' value='$id'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>";

                      if ($bimestre == 'Frequência') {
                        echo "<td>Não pode ser excluído</td>";
                      } else {
                        echo "<form method='post' action='#'>
                        <input type='hidden' name='id' value='$id'>
                        <input type='hidden' name='operacao' value='excluir'>
                        <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                        </form>"; 
                      }
                      
                       echo "<td><a href='provaResultados.php?id=$id'>Ver resultados</a></td></tr>";
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
  <script>
  let turma = false
  let disciplina = false

  function setTurma(el) {
    turma = el.value
    getOptions()
  }

  function setDisciplina(el) {
    disciplina = el.value
    getOptions()
  }

  function getOptions() {
    if (turma && turma.length > 0 && disciplina && disciplina.length > 0) {
      $.ajax({
        url: 'aplicarProva.php',
        type: 'GET',
        dataType: 'html',
        data: {
          turma,
          disciplina
        },
        success: function(data) {
          $('#idAplicar').html(data)
        },
        error: function(err) {
          console.log(err)
        }
      })
    }
  }
  </script>
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