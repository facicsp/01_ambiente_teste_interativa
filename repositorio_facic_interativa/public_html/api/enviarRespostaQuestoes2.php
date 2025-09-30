<?php

include 'hasAccess.php';

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

$seguranca = new Seguranca();
$idAluno   = $seguranca->antisql($_POST["id"]);
$idAplicar = $seguranca->antisql($_POST["idAplicar"]);
$respostas = $_POST["respostas"];

$html = "";

$result  = mysqli_query($conexao, "SELECT idProva FROM aplicarprova WHERE idAplicarProva = '$idAplicar'");
$idProva = mysql_result($result, 0, "idProva");

for ($i=0; $i<sizeof($respostas); $i++) {
  
  $idQuestao = $respostas[$i]["idQuestao"];
  $resposta  = $respostas[$i]["resposta"];

  $resultQuestao = mysqli_query($conexao, "SELECT * FROM questao2 WHERE idQuestao = '$idQuestao'");
  $descricao     = mysql_result($resultQuestao, 0, "descricao");
  $tipo          = mysql_result($resultQuestao, 0, "tipo");
  $peso          = mysql_result($resultQuestao, 0, "peso");

  $html .= "<p>".($i+1)." - <span style='color: red'>#$idQuestao</span> $descricao</p>";

  $nota = '';

  if ($tipo === "objetiva") {
    $result = mysqli_query($conexao, "SELECT * FROM alternativa WHERE idQuestao = '$idQuestao'");
    $nota = 0;

    for ($j=0; $j < mysqli_num_rows($result); $j++) { 
      $idAlternativa = mysql_result($result, $j, "idalternativa");
      $alternativa = mysql_result($result, $j, "alternativa");
      $correta = mysql_result($result, $j, "correta");

      if ($resposta == $idAlternativa && $correta == 'sim') $nota = $peso;

      $html .= "<p>" . getLetra($j) . ") $alternativa " . ($resposta == $idAlternativa ? "<span style='color: blue'>(escolhida)</span>" : "") . "</p>";
    }
  } else {
    $html .= "<p><span style='color: blue'>Resposta: </span> $resposta</p>";
  }

  $html .= "<hr>";

  mysqli_query($conexao, "INSERT INTO lista_resposta VALUES(NULL, '$resposta', '$nota', '$idProva', '$idQuestao', '$idAluno')");
}

$assunto = "Confirmação de envio de prova";
$conteudo = "FACIC INTERATIVA";
$header  = "Content-type: text/html; charset=iso-8859-1\n";
$header .= "From: FACIC INTERATIVA<sistemafacic@ava24horas.com>";

$result = mysqli_query($conexao, "SELECT email FROM usuario WHERE idUsuario = '$idAluno'");
$email = mysql_result($result, 0, "email");

$mail = ("$email");
mail($mail, $assunto, $html, $header);

echo json_encode(true);