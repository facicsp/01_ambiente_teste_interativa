<?php
/**
 * VERSÃO LIMPA - Garante retorno JSON puro
 * Sem injeção de scripts
 */

// Iniciar output buffering ANTES de tudo
ob_start();

session_start();

// Verificar autenticação
if (!isset($_SESSION["usuario"]) || $_SESSION["tipo"] != "professor") {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Acesso negado"]);
    exit;
}

// Pegar dados
$data = $_POST;

// Incluir conexão (pode injetar script, mas vamos limpar depois)
include 'conexao.php';

// Inicializar segurança
$seguranca = new Seguranca();
$idProfessor = $_SESSION["id"];

// Validação
if (!isset($data["titulo"]) || trim($data["titulo"]) == "") {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Título da prova é obrigatório"]);
    exit;
}

if (!isset($data["descricao"]) || trim($data["descricao"]) == "") {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Descrição da questão é obrigatória"]);
    exit;
}

// Sanitização
$titulo = $seguranca->antisql($data["titulo"]);
$descricao = $seguranca->antisql($data["descricao"]);
$correta = isset($data["correta"]) ? $seguranca->antisql($data["correta"]) : false;
$idProva = isset($data["idProva"]) && $data["idProva"] && $data["idProva"] !== 'false' ? $seguranca->antisql($data["idProva"]) : false;
$tipo = isset($data["tipo"]) ? $seguranca->antisql($data["tipo"]) : 'objetiva';
$peso = isset($data["peso"]) ? str_replace(",", ".", $seguranca->antisql($data["peso"])) : 1;

// Validar peso
if (!is_numeric($peso) || $peso < 0 || $peso > 10) {
    $peso = 1;
}

// Gerenciar prova
if (isset($_SESSION['idProva']) && $_SESSION['idProva'] && $idProva) {
    $idProva = $_SESSION['idProva'];
} else {
    // Criar nova prova
    $queryInsertProva = "INSERT INTO prova VALUES (NULL, '$titulo', '$idProfessor')";
    $resultInsertProva = mysql_query($queryInsertProva);

    if (!$resultInsertProva) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(["error" => "Erro ao criar prova: " . mysql_error()]);
        exit;
    }

    // Recuperar ID
    $querySelectProva = "SELECT idProva FROM prova WHERE titulo = '$titulo' AND idProfessor = '$idProfessor' ORDER BY idProva DESC LIMIT 1";
    $resultSelectProva = mysql_query($querySelectProva);

    if (!$resultSelectProva || mysql_num_rows($resultSelectProva) == 0) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(["error" => "Erro ao recuperar ID da prova"]);
        exit;
    }

    $idProva = mysql_result($resultSelectProva, 0, 'idProva');
    $_SESSION['idProva'] = $idProva;
}

// Processar alternativas
$alternativas = [];
for ($i = 0; $i <= 7; $i++) {
    if (isset($data[$i]) && trim($data[$i]) != "") {
        $alternativas[$i] = $seguranca->antisql($data[$i]);
    }
}

// Validar questões objetivas
if ($tipo == "objetiva") {
    if (count($alternativas) < 2) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(["error" => "Questão objetiva precisa ter pelo menos 2 alternativas"]);
        exit;
    }

    if ($correta === false || !isset($alternativas[$correta])) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(["error" => "Selecione a alternativa correta"]);
        exit;
    }
}

// Inserir questão
$queryInsertQuestao = "INSERT INTO questao2 VALUES (NULL, '$descricao', '$tipo', '$peso', '$idProva', NULL)";
$resultInsertQuestao = mysql_query($queryInsertQuestao);

if (!$resultInsertQuestao) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Erro ao criar questão: " . mysql_error()]);
    exit;
}

// Recuperar ID da questão
$querySelectQuestao = "SELECT idQuestao FROM questao2 WHERE descricao = '$descricao' AND tipo = '$tipo' AND idProva = '$idProva' ORDER BY idQuestao DESC LIMIT 1";
$resultSelectQuestao = mysql_query($querySelectQuestao);

if (!$resultSelectQuestao || mysql_num_rows($resultSelectQuestao) == 0) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Erro ao recuperar ID da questão"]);
    exit;
}

$idQuestao = mysql_result($resultSelectQuestao, 0, 'idQuestao');

// Inserir alternativas
if ($tipo == "objetiva") {
    foreach ($alternativas as $index => $textoAlternativa) {
        $altCorreta = ($correta == $index) ? "sim" : "nao";
        $queryInsertAlt = "INSERT INTO alternativa VALUES (NULL, '$textoAlternativa', '$altCorreta', '$idQuestao')";
        mysql_query($queryInsertAlt);
    }
}

// LIMPAR TODO O BUFFER E RETORNAR APENAS JSON PURO
ob_end_clean();

// Garantir que não há nada antes do JSON
header('Content-Type: application/json');
echo $idProva; // Retorna apenas o número (formato esperado pelo JS antigo)

// Não adicionar nada depois!
exit;
?>
