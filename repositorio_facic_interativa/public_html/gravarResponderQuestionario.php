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

    include 'LoginRestrito/conexao.php';

    $idQuestao = $_POST["txtIdQuestao"];
    $resposta  = $_POST["txtResposta"];
    $idAluno   = $_SESSION['id'];
    $html = "";
    
    for ($i=0; $i<sizeof($idQuestao); $i++) {
       mysqli_query($conexao, "INSERT INTO respostas VALUES (NULL, '".$idQuestao[$i]."', '".$resposta[$i]."', '$idAluno')");

      $escolha = getLetra($resposta[$i]);
      $result = mysqli_query($conexao, "SELECT idQuestao, descricao, $escolha FROM questao WHERE idQuestao = '".$idQuestao[$i]."'");

      $_idQuestao = mysql_result($result, 0, "idQuestao");
      $_descricao = mysql_result($result, 0, "descricao");
      $_escolha = mysql_result($result, 0, $escolha);

      $html .= "<p>".($i+1)." - <span style='color: red'>#$_idQuestao</span> $_descricao</p>";
      $html .= "<p>$escolha) $_escolha</p><hr>";
    }

    $assunto = "Confirmação de envio de questionário";
    $conteudo = "FACIC INTERATIVA";
    $header  = "Content-type: text/html; charset=iso-8859-1\n";
    $header .= "From: FACIC INTERATIVA<sistemafacic@ava24horas.com>";

    $result = mysqli_query($conexao, "SELECT email FROM usuario WHERE idUsuario = '$idAluno'");
    $email = mysql_result($result, 0, "email");

    $mail = ("$email");
    mail($mail, $assunto, $html, $header);

    echo "<script>alert('Gravação realizada com sucesso!'); window.location = 'responder.php';</script>";
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