<?php

session_start();

if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "professor") {

  // Verifica se é uma requisição multipart/form-data (com arquivo)
  if (!empty($_POST)) {
    $data = $_POST;
  } else {
    // Para requisições JSON tradicionais
    $data = json_decode(file_get_contents("php://input"), true);
  }
  
  include 'LoginRestrito/conexao.php';

  $seguranca    = new Seguranca();
  $idProfessor  = $_SESSION["id"];
  $titulo       = $seguranca->antisql($data["titulo"]);
  $descricao    = $seguranca->antisql($data["descricao"]);
  $correta      = $seguranca->antisql($data["correta"]);
  $idProva      = $seguranca->antisql($data["idProva"]);
  $tipo         = $seguranca->antisql($data["tipo"]);
  $peso         = str_replace(",", ".", $seguranca->antisql($data["peso"]));

  if (isset($_SESSION['idProva']) && $idProva) {
    $idProva = $_SESSION['idProva'];
  } else {
    mysqli_query($conexao, "INSERT INTO prova VALUES (NULL, '$titulo', '$idProfessor')");
    $result  = mysqli_query($conexao, "SELECT idProva FROM prova WHERE titulo = '$titulo' AND idProfessor = '$idProfessor' ORDER BY idProva DESC LIMIT 1");
    $idProva = mysql_result($result, 0, 'idProva');
    $_SESSION['idProva'] = $idProva;
  }

  $a = $data["0"] ? $seguranca->antisql($data["0"]) : false;
  $b = $data["1"] ? $seguranca->antisql($data["1"]) : false;
  $c = $data["2"] ? $seguranca->antisql($data["2"]) : false;
  $d = $data["3"] ? $seguranca->antisql($data["3"]) : false;
  $e = $data["4"] ? $seguranca->antisql($data["4"]) : false;
  $f = $data["5"] ? $seguranca->antisql($data["5"]) : false;
  $g = $data["6"] ? $seguranca->antisql($data["6"]) : false;
  $h = $data["7"] ? $seguranca->antisql($data["7"]) : false;

  // Processar anexo se foi enviado
  $anexo = "NULL";
  if (isset($_FILES["anexo"]) && $_FILES['anexo']['error'] == 0) {
    $_UP['pasta'] = './uploads/questoes/';
    $_UP['tamanho'] = 1024 * 1024 * 10; // 10MB
    $_UP['extensoes'] = array('pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx');

    // Criar diretório se não existir
    if (!is_dir($_UP['pasta'])) {
      mkdir($_UP['pasta'], 0777, true);
    }

    $extensao = strtolower(pathinfo($_FILES['anexo']['name'], PATHINFO_EXTENSION));

    if (in_array($extensao, $_UP['extensoes']) && $_FILES['anexo']['size'] <= $_UP['tamanho']) {
      $nomeArquivo = md5($_FILES['anexo']['tmp_name']) . time() . "." . $extensao;
      $caminhoCompleto = $_UP['pasta'] . $nomeArquivo;

      if (move_uploaded_file($_FILES['anexo']['tmp_name'], $caminhoCompleto)) {
        $anexo = "'$caminhoCompleto'";
      }
    }
  }

  mysqli_query($conexao, "INSERT INTO questao2 VALUES (NULL, '$descricao', '$tipo', '$peso', '$idProva')");
  $result = mysqli_query($conexao, "SELECT idQuestao FROM questao2 WHERE descricao = '$descricao' AND tipo = '$tipo' AND idProva = '$idProva' ORDER BY idQuestao DESC LIMIT 1");
  $idQuestao = mysql_result($result, 0, 'idQuestao');

  // Se houver anexo, salvar em arquivo de texto para referência futura
  if ($anexo != "NULL") {
    $infoAnexo = "Questao ID: $idQuestao\nProva ID: $idProva\nAnexo: $anexo\nData: " . date('Y-m-d H:i:s') . "\n\n";
    file_put_contents('./uploads/questoes/anexos_log.txt', $infoAnexo, FILE_APPEND | LOCK_EX);
  }

  if ($tipo == "objetiva") {
    for ($i=0; $i<=7; $i++) { 
      if (isset($data[$i])) {
        $alternativa = $seguranca->antisql($data[$i]);
        $altCorreta = $correta == $i ? "sim" : "nao";
        mysqli_query($conexao, "INSERT INTO alternativa VALUES (NULL, '$alternativa', '$altCorreta', '$idQuestao')");
      }
    }
  }
  
  echo json_encode($idProva);
} else {  
  echo json_encode(false);
} 