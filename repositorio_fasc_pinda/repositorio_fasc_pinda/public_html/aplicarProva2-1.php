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
    if (el.value > 0) {
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
    input[type="image"] {
    width: 32px;
    height: 32px;
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
          <h3>Aplicar Formulário</h3>
        </div>
        <form method="post" action="gravarAplicarProva2.php">
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
                <td>Formulário</td>
                <td style="display: flex">
                  <select name="txtProva" required>
                    <option value="" selected disabled>::Escolha um formulário que ainda não foi aplicado::</option>
                    <?php
                        $result = mysqli_query($conexao, "SELECT * FROM prova WHERE idProfessor = " . $_SESSION["id"]);
                        $linhas = mysqli_num_rows($result);

                        for ($i=0; $i<$linhas; $i++) { 
                            $idProva = mysql_result($result, $i, "idProva");
                            $titulo  = mysql_result($result, $i, "titulo");

                            $resultQuestoes = mysqli_query($conexao, "SELECT COUNT(*) AS questoes FROM questao2 WHERE idProva = $idProva");
                            
                            if (mysql_result($resultQuestoes, 0, "questoes") > 0) {
                              $resultAplicado = mysqli_query($conexao, "SELECT idProva FROM aplicarprova WHERE idProva = $idProva");

                              if (mysqli_num_rows($resultAplicado) == 0) {
                                $titulo = $titulo == "" ? "sem título" : $titulo;
                                echo "<option value='$idProva'>$titulo</option>";
                              }
                            }
                        }
                    ?>
                  </select>
                  <a href='cadastroProva2.php' class="botao" style="
                    text-align: center;
                    color: #FFF;
                    margin-left: 10px;
                    border: none;
                    border-radius: 50px;
                    background: green;
                    font-weight: normal;">Novo</a>
                    <a href='visualizarProvas2.php' class="botao" style="
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
                  <select name="txtTipo" onchange="isAvaliativo(this)">
                    <option value="0" selected>Frequência (Predefinidos pela secretaria)</option>
                    <option value="1">Questionário: 1° Bimestre (Isso irá compor a nota "A. Virtual l")</option>
                    <option value="2">Questionário: 2° Bimestre (Isso irá compor a nota "A. Virtual ll")</option>
                    <option value="10">Prova: P1</option>
                    <option value="20">Prova: P2</option>
                    <option value="30">Prova: SUB</option>
                    <option value="40">Prova: EXAME</option>
                  
                    <!-- <option value="0" selected>Frequência (Predefinidos pela secretaria)</option>
                    <option value="1">Avaliativo (AV1 ou AV2)</option>
                    <option value="2">Prova (P1, P2, Sub ou Exame)</option> -->
                  </select>
                </td>
              </tr>

              <tr class="hidden">
                <td>Contabilizar em</td>
                <td>
                  <select name="txtContabiliza">
                    <option value="1">1° Bimestre (Isso irá compor a nota "A. Virtual l")</option>
                    <option value="2">2° Bimestre (Isso irá compor a nota "A. Virtual ll")</option>
                    <option value="10" selected>p1</option>
                    <option value="20">p2</option>
                  </select>
                </td>
              </tr>

              <tr class="isFrequencia">
                <td>Frequência</td>
                <td>
                  <select name="txtIdAplicar" id="idAplicar">
                    <option value="" selected disabled>::Selecione a turma e a disciplina::</option>
                  </select>
                </td>
              </tr>
              
              <tr class="isAvaliativo hidden">
                <td>Data de abertura</td>
                <td>
                  <input type="date" name="txtData">
                </td>
              </tr>

              <tr class="isAvaliativo hidden">
                <td>Data de entrega</td>
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
                            //$sql = "SELECT idturma, turma, semestre FROM turma 
                            //  WHERE semestre = '{$_SESSION["semestre"]}' AND 
                            //  idturma in (select disciplina.idTurma from disciplina where idprofessor = '$idProfessor')";
                          
                          include "funcaoDisciplinas.php";
                          $sql = sqlTurma($idProfessor, $_SESSION["semestre"]);
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
                            //$sql = "SELECT d.*, t.turma FROM disciplina d,turma t 
                            //  WHERE d.semestre = '{$_SESSION["semestre"]}' 
                            //  AND d.idTurma = t.idTurma AND d.idProfessor = '$idProfessor' 
                            //  ORDER BY d.disciplina";
                          
                            $sql = sqlDisciplina($idProfessor, $_SESSION["semestre"]);
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
                                $idTurma = mysql_result($resultados, $i, "idTurma");
                                if ($_SESSION["tipo"] == "professor") {
                                    if ($i == 0) $listaDisciplina .= $idDisciplina;
                                    else $listaDisciplina .= "," . $idDisciplina;
                                }
                                echo "<option value='$idDisciplina' data-turma='$idTurma'>$disciplina - $turma</option>";
                            }
                        }
                    ?>
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
          <div class="consulta grid-50">
            <form method="post" action="#">
              <input type="text" name="txtConsulta" placeholder="Pesquisar por nome">
              <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
            </form>
          </div>
          <div class="consulta grid-50">
            <form method="post" action="#">
          <select name="txtDisciplina" required>
                    <option value="" selected disabled>Pesquisar por disciplina</option>
                    <?php
                        if ($_SESSION["tipo"] == "professor") {
                            $idProfessor = $_SESSION["id"];
                            $sql = "SELECT d.*, t.turma FROM disciplina d,turma t 
                              WHERE d.semestre = '{$_SESSION["semestre"]}' 
                              AND d.idTurma = t.idTurma AND d.idProfessor = '$idProfessor' 
                              ORDER BY d.disciplina";
                          
                            $sql = sqlDisciplina($idProfessor, $_SESSION["semestre"]);
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
                                $idTurma = mysql_result($resultados, $i, "idTurma");
                                if ($_SESSION["tipo"] == "professor") {
                                    if ($i == 0) $listaDisciplina .= $idDisciplina;
                                    else $listaDisciplina .= "," . $idDisciplina;
                                }
                                echo "<option value='$idDisciplina' data-turma='$idTurma'>$disciplina - $turma</option>";
                            }
                        }
                    ?>
                  </select>
          
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
          
          $where = "";
          if (isset($_POST["txtDisciplina"]) && $_POST["txtDisciplina"] != "") {
            $where = " AND disciplina.idDisciplina = '{$_POST["txtDisciplina"]}'";
          }
          
            $resultados = mysqli_query($conexao, "SELECT * FROM aplicarprova 
              LEFT JOIN turma ON turma.idTurma = aplicarprova.idTurma 
              LEFT JOIN disciplina ON disciplina.idDisciplina = aplicarprova.idDisciplina 
              WHERE aplicarprova.idProfessor = '$idProfessor' AND aplicarprova.idDisciplina IN($listaDisciplina) AND titulo LIKE '%$titulo%' $where");
            $linhas     = mysqli_num_rows($resultados);
            
            if ($linhas > 0) {
                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                  <tr>
                  <td>Título</td>
                  <td>Data de abertura</td>
                  <td>Data de entrega</td>
                  <td>Tipo</td>
                  <td>Disciplina - Turma</td>
                  <td colspan='3'>Operações</td>
                  <td>Dissertativa</td>
                  <td>Objetivas</td>
                  </tr>";

                for ($i = 0; $i < $linhas; $i++) {
                    $id = mysql_result($resultados, $i, "idAplicarProva");
                    $titulo = mysql_result($resultados, $i, "titulo");
                    $abertura = mysql_result($resultados, $i, "abertura");
                    $data = mysql_result($resultados, $i, "fechamento");
                    $bimestre = mysql_result($resultados, $i, "bimestre");
                    $idProva = mysql_result($resultados, $i, "idProva");
                    $turma = mysql_result($resultados, $i, "turma");
                    $disciplina = mysql_result($resultados, $i, "disciplina");
                  
                    $b1 = $b2 = $p1 = $p2 = $ex = $su = '';

                    if ($bimestre == 0) $bimestre = 'Frequência';
                    else if ($bimestre == 1) { $bimestre = 'Bimestre 1'; $b1 = 'selected'; }
                    else if ($bimestre == 2) { $bimestre = 'Bimestre 2'; $b2 = 'selected'; }
                    else if ($bimestre == 10) { $bimestre = 'P1'; $p1 = 'selected'; }
                    else if ($bimestre == 20) { $bimestre = 'P2'; $p2 = 'selected'; }
                    else if ($bimestre == 30) { $bimestre = 'SUB'; $su = 'selected'; }
                    else { $bimestre = 'EXAME'; $ex = 'selected'; }

                    echo "<form method='post' action='alterarAplicarProva2.php'>
                    <tr><td style='width: 19%'><input type='text' value='$titulo' name='txtTitulo'></td>
                    <td style='width: 8%'><input type='date' value='".date_format(date_create($abertura), "Y-m-d")."' name='txtAbertura'></td>
                    <td style='width: 8%'><input type='date' value='".date_format(date_create($data), "Y-m-d")."' name='txtData' ".($bimestre == 'Frequência' ? 'readonly title="Esta data foi definida pela secretaria"' : '')."></td>
                    <td style='width: 12%'>";
                      
                  if ($bimestre == 'Frequência') {
                    echo "<input type='text' value='$bimestre' title='Frequência (Não pode ser alterado)' readonly>";
                    echo "<input type='hidden' value='0'> name='txtContabiliza'";
                  } else {
                    echo "<select name='txtContabiliza'>
                        <option value='1' $b1>Questionário: 1° Bimestre (Isso irá compor a nota 'A. Virtual l')</option>
                        <option value='2' $b2>Questionário: 2° Bimestre (Isso irá compor a nota 'A. Virtual ll')</option>
                        <option value='10' $p1>Prova: P1</option>
                        <option value='20' $p2>Prova: P2</option>
                        <option value='30' $su>Prova: SUB</option>
                        <option value='40' $ex>Prova: EXAME</option>
                      </select>";
                  }
                      
                      echo "</td> <td>$disciplina - $turma</td>";
                      
                      echo "<input type='hidden' name='id' value='$id'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>";
                  
                  echo "<form method='get' action='visualizarProvas2.php'>
                        <input type='hidden' name='id' value='$idProva'>
                        <td><input type='image' name='img_atualizar' src='imagens/kblackbox.png' border='0' title='Visualizar Questionário'></td>
                        </form>"; 
                  
                  //echo "<td><a href='visualizarProvas2.php?id=$idProva' title='Visualizar Questionário'>Visualizar</a></td>";

                      if ($bimestre == 'Frequência') {
                        echo "<td>Não pode ser excluído</td>";
                      } else {
                        echo "<form method='post' action='#' onsubmit=\"return confirm('Deseja remover esse registro?');\">
                        <input type='hidden' name='id' value='$id'>
                        <input type='hidden' name='operacao' value='excluir'>
                        <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                        </form>"; 
                      }
                      
                       echo "<td><a href='corrigirProva.php?id=$id&idProva=$idProva'>Corrigir</a></td>";
                       echo "<td><a href='visualizarObjetiva.php?id=$id&idProva=$idProva'>Visualizar</a></td></tr>";
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
      
    $('select[name=txtDisciplina] option').removeClass('hidden');
    $('select[name=txtDisciplina] option[data-turma!='+turma+']').addClass('hidden');
      
    getOptions()
  }

  function setDisciplina(el) {
    disciplina = el.value
    getOptions()
  }

  function getOptions() {
    if (turma && turma.length > 0 && disciplina && disciplina.length > 0) {
      $.ajax({
        url: 'aplicarProva2.php',
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