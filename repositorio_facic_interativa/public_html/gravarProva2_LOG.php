<?php
/**
 * ARQUIVO: gravarProva2_LOG.php
 * FUNÇÃO: Versão DEBUG com logs completos para diagnosticar problemas de salvamento
 * DATA: 01/10/2025
 */

// Iniciar buffer de saída para capturar tudo
ob_start();

// Criar arquivo de log
$logFile = 'log_gravar_prova_' . date('Y-m-d_H-i-s') . '.txt';
$logPath = __DIR__ . '/' . $logFile;

function writeLog($message) {
    global $logPath;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logPath, "[$timestamp] $message\n", FILE_APPEND);
}

writeLog("========================================");
writeLog("INÍCIO DO PROCESSAMENTO - gravarProva2_LOG.php");
writeLog("========================================");

// ============================================================
// 1. VERIFICAR SESSÃO
// ============================================================
session_start();

writeLog("1. VERIFICAÇÃO DE SESSÃO");
writeLog("Session ID: " . session_id());
writeLog("Session usuario: " . (isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : "NÃO DEFINIDO"));
writeLog("Session tipo: " . (isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "NÃO DEFINIDO"));
writeLog("Session id: " . (isset($_SESSION["id"]) ? $_SESSION["id"] : "NÃO DEFINIDO"));
writeLog("Session idProva: " . (isset($_SESSION["idProva"]) ? $_SESSION["idProva"] : "NÃO DEFINIDO"));

if (!isset($_SESSION["usuario"]) || $_SESSION["tipo"] != "professor") {
    writeLog("❌ ERRO: Acesso negado - usuário não autenticado ou não é professor");
    echo json_encode(["error" => "Acesso negado", "log" => $logFile]);
    exit;
}

writeLog("✅ Sessão validada com sucesso");

// ============================================================
// 2. CAPTURAR DADOS RECEBIDOS
// ============================================================
writeLog("\n2. DADOS RECEBIDOS");
writeLog("Método: " . $_SERVER['REQUEST_METHOD']);
writeLog("Content-Type: " . (isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : "NÃO DEFINIDO"));

writeLog("\n--- $_POST ---");
writeLog(print_r($_POST, true));

writeLog("\n--- $_FILES ---");
writeLog(print_r($_FILES, true));

writeLog("\n--- RAW INPUT ---");
$rawInput = file_get_contents("php://input");
writeLog("Tamanho: " . strlen($rawInput) . " bytes");
writeLog("Primeiros 500 caracteres: " . substr($rawInput, 0, 500));

// Usar $_POST
$data = $_POST;

// ============================================================
// 3. INCLUIR DEPENDÊNCIAS
// ============================================================
writeLog("\n3. INCLUIR DEPENDÊNCIAS");

if (!file_exists('conexao.php')) {
    writeLog("❌ ERRO: Arquivo conexao.php não encontrado");
    echo json_encode(["error" => "Arquivo de conexão não encontrado", "log" => $logFile]);
    exit;
}

writeLog("Incluindo conexao.php...");
include 'conexao.php';

// Verificar se a classe Seguranca foi carregada
if (!class_exists('Seguranca')) {
    writeLog("❌ ERRO: Classe Seguranca não encontrada");
    echo json_encode(["error" => "Classe Seguranca não encontrada", "log" => $logFile]);
    exit;
}

writeLog("✅ Classe Seguranca carregada");
$seguranca = new Seguranca();
$idProfessor = $_SESSION["id"];
writeLog("ID Professor: " . $idProfessor);

// ============================================================
// 4. VALIDAÇÃO DE DADOS DE ENTRADA
// ============================================================
writeLog("\n4. VALIDAÇÃO DE DADOS");

// Verificar título
if (!isset($data["titulo"]) || trim($data["titulo"]) == "") {
    writeLog("❌ ERRO: Título não fornecido ou vazio");
    echo json_encode(["error" => "Título da prova é obrigatório", "log" => $logFile]);
    exit;
}
writeLog("✅ Título: " . $data["titulo"]);

// Verificar descrição
if (!isset($data["descricao"]) || trim($data["descricao"]) == "") {
    writeLog("❌ ERRO: Descrição não fornecida ou vazia");
    echo json_encode(["error" => "Descrição da questão é obrigatória", "log" => $logFile]);
    exit;
}
writeLog("✅ Descrição: " . substr($data["descricao"], 0, 100) . "...");

// ============================================================
// 5. SANITIZAÇÃO DE DADOS
// ============================================================
writeLog("\n5. SANITIZAÇÃO DE DADOS");

$titulo = $seguranca->antisql($data["titulo"]);
$descricao = $seguranca->antisql($data["descricao"]);
$correta = isset($data["correta"]) ? $seguranca->antisql($data["correta"]) : false;
$idProva = isset($data["idProva"]) && $data["idProva"] ? $seguranca->antisql($data["idProva"]) : false;
$tipo = isset($data["tipo"]) ? $seguranca->antisql($data["tipo"]) : 'objetiva';
$peso = isset($data["peso"]) ? str_replace(",", ".", $seguranca->antisql($data["peso"])) : 1;

writeLog("Título sanitizado: " . $titulo);
writeLog("Tipo: " . $tipo);
writeLog("Peso: " . $peso);
writeLog("Correta: " . ($correta !== false ? $correta : "não definido"));
writeLog("ID Prova passado: " . ($idProva ? $idProva : "não definido"));

// Validar peso
if (!is_numeric($peso) || $peso < 0 || $peso > 10) {
    writeLog("⚠️ AVISO: Peso inválido ($peso), ajustando para 1");
    $peso = 1;
}

// ============================================================
// 6. GERENCIAR PROVA (CRIAR OU USAR EXISTENTE)
// ============================================================
writeLog("\n6. GERENCIAMENTO DA PROVA");

if (isset($_SESSION['idProva']) && $idProva) {
    // Usar prova existente
    $idProva = $_SESSION['idProva'];
    writeLog("✅ Usando prova existente da sessão: " . $idProva);
} else {
    writeLog("Criando nova prova...");

    $queryInsertProva = "INSERT INTO prova VALUES (NULL, '$titulo', '$idProfessor')";
    writeLog("Query INSERT prova: " . $queryInsertProva);

    $resultInsertProva = mysql_query($queryInsertProva);

    if (!$resultInsertProva) {
        $erro = mysql_error();
        writeLog("❌ ERRO ao inserir prova: " . $erro);
        echo json_encode(["error" => "Erro ao criar prova: " . $erro, "log" => $logFile]);
        exit;
    }

    writeLog("✅ Prova inserida com sucesso");

    // Recuperar ID da prova
    $querySelectProva = "SELECT idProva FROM prova WHERE titulo = '$titulo' AND idProfessor = '$idProfessor' ORDER BY idProva DESC LIMIT 1";
    writeLog("Query SELECT prova: " . $querySelectProva);

    $resultSelectProva = mysql_query($querySelectProva);

    if (!$resultSelectProva) {
        $erro = mysql_error();
        writeLog("❌ ERRO ao recuperar ID da prova: " . $erro);
        echo json_encode(["error" => "Erro ao recuperar ID da prova: " . $erro, "log" => $logFile]);
        exit;
    }

    if (mysql_num_rows($resultSelectProva) == 0) {
        writeLog("❌ ERRO: Nenhuma prova encontrada após INSERT");
        echo json_encode(["error" => "Erro ao recuperar ID da prova - nenhum registro encontrado", "log" => $logFile]);
        exit;
    }

    $idProva = mysql_result($resultSelectProva, 0, 'idProva');
    $_SESSION['idProva'] = $idProva;
    writeLog("✅ ID da prova recuperado e salvo na sessão: " . $idProva);
}

// ============================================================
// 7. PROCESSAR ALTERNATIVAS
// ============================================================
writeLog("\n7. PROCESSAMENTO DE ALTERNATIVAS");

$alternativas = [];
for ($i = 0; $i <= 7; $i++) {
    if (isset($data[$i]) && trim($data[$i]) != "") {
        $alternativas[$i] = $seguranca->antisql($data[$i]);
        writeLog("Alternativa $i: " . substr($alternativas[$i], 0, 50) . "...");
    }
}

writeLog("Total de alternativas: " . count($alternativas));

// Validar questões objetivas
if ($tipo == "objetiva") {
    writeLog("Validando questão objetiva...");

    if (count($alternativas) < 2) {
        writeLog("❌ ERRO: Menos de 2 alternativas fornecidas");
        echo json_encode(["error" => "Questão objetiva precisa ter pelo menos 2 alternativas", "log" => $logFile]);
        exit;
    }

    if ($correta === false || !isset($alternativas[$correta])) {
        writeLog("❌ ERRO: Alternativa correta não definida ou inválida");
        echo json_encode(["error" => "Selecione a alternativa correta", "log" => $logFile]);
        exit;
    }

    writeLog("✅ Questão objetiva validada - Alternativa correta: $correta");
}

// ============================================================
// 8. INSERIR QUESTÃO NO BANCO
// ============================================================
writeLog("\n8. INSERÇÃO DA QUESTÃO");

$queryInsertQuestao = "INSERT INTO questao2 VALUES (NULL, '$descricao', '$tipo', '$peso', '$idProva', NULL)";
writeLog("Query INSERT questão: " . $queryInsertQuestao);

$resultInsertQuestao = mysql_query($queryInsertQuestao);

if (!$resultInsertQuestao) {
    $erro = mysql_error();
    writeLog("❌ ERRO ao inserir questão: " . $erro);
    echo json_encode(["error" => "Erro ao criar questão: " . $erro, "log" => $logFile]);
    exit;
}

writeLog("✅ Questão inserida com sucesso");

// Recuperar ID da questão
$querySelectQuestao = "SELECT idQuestao FROM questao2 WHERE descricao = '$descricao' AND tipo = '$tipo' AND idProva = '$idProva' ORDER BY idQuestao DESC LIMIT 1";
writeLog("Query SELECT questão: " . $querySelectQuestao);

$resultSelectQuestao = mysql_query($querySelectQuestao);

if (!$resultSelectQuestao) {
    $erro = mysql_error();
    writeLog("❌ ERRO ao recuperar ID da questão: " . $erro);
    echo json_encode(["error" => "Erro ao recuperar ID da questão: " . $erro, "log" => $logFile]);
    exit;
}

if (mysql_num_rows($resultSelectQuestao) == 0) {
    writeLog("❌ ERRO: Nenhuma questão encontrada após INSERT");
    echo json_encode(["error" => "Erro ao recuperar ID da questão - nenhum registro encontrado", "log" => $logFile]);
    exit;
}

$idQuestao = mysql_result($resultSelectQuestao, 0, 'idQuestao');
writeLog("✅ ID da questão recuperado: " . $idQuestao);

// ============================================================
// 9. INSERIR ALTERNATIVAS
// ============================================================
writeLog("\n9. INSERÇÃO DAS ALTERNATIVAS");

if ($tipo == "objetiva") {
    foreach ($alternativas as $index => $textoAlternativa) {
        $altCorreta = ($correta == $index) ? "sim" : "nao";

        $queryInsertAlt = "INSERT INTO alternativa VALUES (NULL, '$textoAlternativa', '$altCorreta', '$idQuestao')";
        writeLog("Query INSERT alternativa $index: " . $queryInsertAlt);

        $resultInsertAlt = mysql_query($queryInsertAlt);

        if (!$resultInsertAlt) {
            $erro = mysql_error();
            writeLog("❌ ERRO ao inserir alternativa $index: " . $erro);
        } else {
            writeLog("✅ Alternativa $index inserida com sucesso");
        }
    }
} else {
    writeLog("Questão discursiva - não há alternativas para inserir");
}

// ============================================================
// 10. PROCESSAR UPLOAD DE ANEXO
// ============================================================
writeLog("\n10. PROCESSAMENTO DE ANEXO");

if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] == UPLOAD_ERR_OK) {
    writeLog("Anexo detectado:");
    writeLog("- Nome: " . $_FILES['anexo']['name']);
    writeLog("- Tamanho: " . $_FILES['anexo']['size'] . " bytes");
    writeLog("- Tipo: " . $_FILES['anexo']['type']);

    $uploadDir = 'uploads/anexos/';

    if (!is_dir($uploadDir)) {
        writeLog("Criando diretório: " . $uploadDir);
        @mkdir($uploadDir, 0755, true);
    }

    $nomeOriginal = $_FILES['anexo']['name'];
    $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
    $tamanhoArquivo = $_FILES['anexo']['size'];

    $extensoesPermitidas = ['pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx', 'txt'];
    $tamanhoMaximo = 5 * 1024 * 1024;

    if (!in_array($extensao, $extensoesPermitidas)) {
        writeLog("⚠️ AVISO: Extensão não permitida: $extensao");
    } elseif ($tamanhoArquivo > $tamanhoMaximo) {
        writeLog("⚠️ AVISO: Arquivo muito grande: " . $tamanhoArquivo . " bytes");
    } else {
        $novoNome = uniqid('anexo_') . '_' . time() . '.' . $extensao;
        $caminhoDestino = $uploadDir . $novoNome;

        if (@move_uploaded_file($_FILES['anexo']['tmp_name'], $caminhoDestino)) {
            writeLog("✅ Anexo salvo: " . $caminhoDestino);

            // Atualizar questão com caminho do anexo
            $queryUpdateAnexo = "UPDATE questao2 SET anexo = '$caminhoDestino' WHERE idQuestao = '$idQuestao'";
            writeLog("Query UPDATE anexo: " . $queryUpdateAnexo);

            if (mysql_query($queryUpdateAnexo)) {
                writeLog("✅ Referência do anexo salva no banco");
            } else {
                writeLog("❌ ERRO ao salvar referência do anexo: " . mysql_error());
            }
        } else {
            writeLog("❌ ERRO ao mover arquivo de upload");
        }
    }
} elseif (isset($_FILES['anexo'])) {
    writeLog("Erro no upload: " . $_FILES['anexo']['error']);
} else {
    writeLog("Nenhum anexo enviado");
}

// ============================================================
// 11. RETORNAR SUCESSO
// ============================================================
writeLog("\n11. FINALIZAÇÃO");
writeLog("✅✅✅ SUCESSO TOTAL - Questão salva com ID: $idQuestao");
writeLog("Retornando ID da prova: $idProva");
writeLog("========================================");
writeLog("FIM DO PROCESSAMENTO");
writeLog("========================================\n\n");

// Retornar sucesso com informações extras
echo json_encode([
    "success" => true,
    "idProva" => $idProva,
    "idQuestao" => $idQuestao,
    "log" => $logFile,
    "message" => "Questão salva com sucesso! Veja o log em: $logFile"
]);

// Capturar saída
$output = ob_get_clean();
if ($output) {
    writeLog("OUTPUT CAPTURADO: " . $output);
}
?>
