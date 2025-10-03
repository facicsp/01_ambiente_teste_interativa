<?php
session_start();

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

if (isset($_SESSION["usuario"])) {
  if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "aluno"){

    include 'conexao.php';
    $seguranca = new Seguranca();

    $idQuestoes = $_POST["txtIdQuestao"];
    $respostas  = $_POST["txtResposta"];
    $idProva    = $_POST["txtIdProva"];
    $idAluno    = $_SESSION['id'];
    $html = "";
    //exit("SELECT * FROM lista_respostas WHERE idaluno = '$idAluno' AND idprova = '$idProva'");
    $_result = mysql_query("SELECT * FROM lista_resposta WHERE idaluno = '$idAluno' AND idprova = '$idProva'");
    if (mysql_num_rows($_result) > 0) exit("Ops! Você já enviou essa prova.");
    
    for ($i=0; $i<sizeof($idQuestoes); $i++) {
      
      $idQuestao = $idQuestoes[$i];
      $resposta  = $seguranca->antisql($respostas[$i]);
      // $tipo      = $tipos[$i];

      $resultQuestao = mysql_query("SELECT * FROM questao2 WHERE idQuestao = '$idQuestao'");
      $descricao     = htmlspecialchars(mysql_result($resultQuestao, 0, "descricao"));
      $tipo          = mysql_result($resultQuestao, 0, "tipo");
      $peso          = mysql_result($resultQuestao, 0, "peso");

      $html .= "<p>".($i+1)." - <span style='color: red'>#$idQuestao</span> $descricao</p>";

      $nota = '';

      if ($tipo === "objetiva") {
        $result = mysql_query("SELECT * FROM alternativa WHERE idQuestao = '$idQuestao'");
        $nota = 0;

        for ($j=0; $j < mysql_num_rows($result); $j++) { 
          $idAlternativa = mysql_result($result, $j, "idalternativa");
          $alternativa = htmlspecialchars(mysql_result($result, $j, "alternativa"));
          $correta = mysql_result($result, $j, "correta");

          if ($resposta == $idAlternativa && $correta == 'sim') $nota = $peso;

          $html .= "<p>" . getLetra($j) . ") $alternativa " . ($resposta == $idAlternativa ? "<span style='color: blue'>(escolhida)</span>" : "") . "</p>";
        }
      } else {
        $html .= "<p><span style='color: blue'>Resposta: </span> " . htmlspecialchars($resposta) . "</p>";
      }

      $html .= "<hr>";

      mysql_query("INSERT INTO lista_resposta VALUES(NULL, '$resposta', '$nota', '$idProva', '$idQuestao', '$idAluno')");
    }

    $assunto = "Confirmação de envio de prova";
    $conteudo = "FACIC INTERATIVA";
    $header  = "Content-type: text/html; charset=iso-8859-1\n";
    $header .= "From: FACIC INTERATIVA<sistemafacic@ava24horas.com>";

    $result = mysql_query("SELECT email FROM usuario WHERE idUsuario = '$idAluno'");
    $email = mysql_result($result, 0, "email");

    $destinatario = ("$email");
    
    // -----------------------------------------------------
    include "./Mailer/form.php";
  
    //$destinatario = "guilhermee.andrade@hotmail.com";
    $assunto = "FACIC INTERATIVA - Confirmação de envio de prova";
    
    $enviou = enviarEmail($destinatario, $assunto, $html);
    // -----------------------------------------------------
    //$enviou = mail($mail, $assunto, $html, $header);

    echo "<script>alert(\"Prova enviada com sucesso! $enviou\"); window.location = 'responder.php';</script>";
}else{
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