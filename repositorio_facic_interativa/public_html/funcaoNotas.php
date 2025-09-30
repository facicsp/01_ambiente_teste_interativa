<?php

function getForum($idAluno, $idDisciplina, $bimestre) {
  $result = mysqli_query($conexao, "SELECT * FROM forumavaliacao WHERE idDisciplina = '$idDisciplina' AND bimestre = '$bimestre'");
  $linhas = mysqli_num_rows($result);

  if ($linhas <= 0) return 0;

  $nota = 0;
  $topicosValidos = "0";

  for ($i=0; $i<$linhas; $i++) {

    $idTopico = mysql_result($result, $i, 'idTopico');

    $topicosValidos .= ", $idTopico";

    $resultForum = mysqli_query($conexao, "SELECT nota FROM notaforum WHERE idAluno = '$idAluno' AND idDisciplina = '$idDisciplina' AND idTopico = '$idTopico'");

    if(mysqli_num_rows($resultForum) > 0) $nota += mysql_result($resultForum, 0, 'nota');
  }
  
  $resultValidos = mysqli_query($conexao, "SELECT * FROM topico WHERE idTopico IN ($topicosValidos)");
  $linhasValidas = mysqli_num_rows($resultValidos);

  $total = $linhasValidas * 100;
  
  return $nota * 100 / $total;
}



function getModulo($idAluno, $idDisciplina, $bimestre) {
  $result = mysqli_query($conexao, "SELECT atividade.nota FROM atividade, aula WHERE atividade.idaluno = '$idAluno' AND atividade.iddisciplina = '$idDisciplina' AND atividade.idaula = aula.idaula AND aula.bimestre = $bimestre");
  $linhas = mysqli_num_rows($result);
  
  if ($linhas <= 0) return 0;

  $nota = 0;

  for ($i=0; $i<$linhas; $i++) $nota += mysql_result($result, $i, "nota");


  return $nota;
//   $total = $linhas * 10;

//   return ($nota * 100 / $total) / 100;
}

function getQuestionario($idAluno, $idDisciplina, $bimestre) {
  $result = mysqli_query($conexao, "SELECT idAplicarProva FROM aplicarprova WHERE idDisciplina = '$idDisciplina' AND bimestre = '$bimestre'");
  $linhas = mysqli_num_rows($result);
  
  if ($linhas <= 0) return 0;

  $acertos = 0;

  for ($i=0; $i<$linhas; $i++) {

    $idAplicar = mysql_result($result, $i, 'idAplicarProva');

    $resultForum = mysqli_query($conexao, "SELECT SUM(IF(correta = resposta, 1, 0)) AS acertos FROM respostas 
                                LEFT JOIN questao ON questao.idQuestao = respostas.idQuestao 
                                LEFT JOIN aplicarprova ON aplicarprova.idProva = questao.idProva 
                                WHERE respostas.idAluno = '$idAluno' AND aplicarprova.idAplicarProva = '$idAplicar' 
                                GROUP BY idAluno");

    if(mysqli_num_rows($resultForum) > 0) $acertos += mysql_result($resultForum, 0, 'acertos');
  }

  $total = $linhas * 10;

  return ($acertos * 100 / $total) / 100;
}
  
function getProva($idAluno, $idDisciplina, $bimestre) {
  //$bimestre = $bimestre . "0"; // 10 || 20 -> p1 ou p2
  
  $resultProvas = mysqli_query($conexao, "SELECT idAplicarProva, idProva, fechamento FROM aplicarprova 
    WHERE idDisciplina = '$idDisciplina' AND bimestre = '$bimestre'");
  $linhasProva = mysqli_num_rows($resultProvas);
  
  if ($linhasProva <= 0) return false;

  
  if ($bimestre == 100 || $bimestre == 200) {
    $soma = array();
  } else {
    $soma = 0;
  }
  
  $data="";
  for ($iProvas=0; $iProvas < $linhasProva; $iProvas++) {
      $idProva = mysql_result($resultProvas, $iProvas, "idProva");
      $data    = mysql_result($resultProvas, $iProvas, "fechamento");

          
      if (strtotime(date("Y-m-d H:i")) > strtotime($data) || $_SESSION["tipo"] == "professor") {
      // o aluno visualiza depois do prazo de envio e o professor visualiza sempre
      
      $result = mysqli_query($conexao, "SELECT correcao FROM lista_resposta
        WHERE idprova = '$idProva' AND idaluno = '$idAluno'");
      $linhas = mysqli_num_rows($result);

      $nota = 0;

      if ($linhas > 0) {
        for ($i=0; $i<$linhas; $i++) { 
          $correcao = mysql_result($result, $i, "correcao");

          if (is_numeric($correcao)) $nota += $correcao;
        }
        
        if ($bimestre == 100 || $bimestre == 200) {
          $soma[] = $nota;
          //echo "<script>console.log('$bimestre -> $nota');</script>";
        } else {
          $soma += $nota;
        }
      }
      
    }
  }
  
  if ($bimestre == 100 || $bimestre == 200) {
    rsort($soma);
    //echo "<script>console.log('".(json_encode($soma))."');</script>";
    return $soma[0];
  } else {
    return $soma;
  }

  
}
    
    
    
    
    