<?php 
  session_start(); 
  include 'LoginRestrito/conexao.php';
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title></title>
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  
  <link rel="stylesheet" href="css/cadastro.css">
</head>

<body>
  <?php
    if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "administrador") {
            include "topo.php";
            include './Util.php';
            $util = new Util();
            $seguranca = new Seguranca();
    ?>

<table width="950px !important" align="center" id="tabelaprincipal">
    <tr>
      <td>
        <div id="titulo">
          <h3>Aplicar questionário</h3>
        </div>

        <hr>
        <center>
          <div class="consulta grid-100">
            <form method="post" action="#">
              <b>Consultar aplicação de questionários</b>
              <input type="text" name="txtConsulta" placeholder="Busque por título ou professor">
              <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
            </form>
          </div>
          <?php

            $seguranca = new Seguranca();

            if (isset($_POST['operacao']) && $_POST['operacao'] == 'excluir') {
                $idAplicarProva = $seguranca->antisql($_POST['id']);
                $bimestre = $seguranca->antisql($_POST['bimestre']);

                if ($bimestre == 0) {
                    mysqli_query($conexao, "UPDATE aplicarprova SET titulo=NULL, idProva=NULL, idProfessor=NULL  WHERE idAplicarProva='$idAplicarProva' AND bimestre='0'");
                } else {
                    mysqli_query($conexao, "DELETE FROM aplicarprova WHERE idAplicarProva = '$idAplicarProva'");
                }
              
              echo "<script>alert('Exclusão realizada com sucesso!');</script>";       
            }
          
            $titulo     = isset($_POST['txtConsulta']) ? $seguranca->antisql($_POST['txtConsulta']) : '';
            $resultados = mysqli_query($conexao, "SELECT professor.nome, disciplina.disciplina, turma.turma, aplicarprova.* FROM aplicarprova 
                LEFT JOIN professor ON professor.idProfessor = aplicarprova.idProfessor 
                LEFT JOIN disciplina ON disciplina.idDisciplina = aplicarprova.idDisciplina 
                LEFT JOIN turma ON turma.idTurma = aplicarprova.idTurma  WHERE titulo LIKE '%$titulo%' OR nome LIKE '%$titulo%'");
            $linhas     = mysqli_num_rows($resultados);
            
            if ($linhas > 0) {
                echo "<table border='0' align='center' cellpadding='5' cellspacing='0'>
                  <tr>
                  <td>#</td>
                  <td>Título</td>
                  <td>Disciplina</td>
                  <td>Turma</td>
                  <td>Professor</td>
                  <td>Tipo</td>
                  <td colspan='1'>Operações</td>
                  <!--<td>Resultados</td>-->
                  </tr>";

                for ($i = 0; $i < $linhas; $i++) {
                    $id = mysql_result($resultados, $i, "idAplicarProva");
                    $titulo = mysql_result($resultados, $i, "titulo");
                    $data = mysql_result($resultados, $i, "fechamento");
                    $bimestre = mysql_result($resultados, $i, "bimestre");
                    $disciplina = mysql_result($resultados, $i, "disciplina");
                    $turma = mysql_result($resultados, $i, "turma");
                    $professor = mysql_result($resultados, $i, "nome");

                    if ($bimestre == 0) $bimestre2 = 'Frequência';
                    else if ($bimestre == 1) $bimestre2 = 'Bimestre 1';
                    else $bimestre2 = 'Bimestre 2';

                    echo "
                    <tr>
                    <td>$id</td>
                    <td>$titulo</td>
                    <td>$disciplina</td>
                    <td>$turma</td>
                    <td>$professor</td>
                    <td>$bimestre2</td>";
                      
                        echo "<td><form method='post' action='visualizarQuestionario.php'>
                        <input type='hidden' name='id' value='$id'>
                        <input type='hidden' name='bimestre' value='$bimestre'>
                        <input type='hidden' name='operacao' value='excluir'>
                        <input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                        </form></tr>";
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