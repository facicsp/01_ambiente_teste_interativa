<?php
/**
 * VERSÃO 2 - COM CAPTURA DE ERROS MÁXIMA
 */

// Configurar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Criar arquivo de log IMEDIATAMENTE
$logFile = 'log_gravar_prova_' . date('Y-m-d_H-i-s') . '.txt';
$logPath = __DIR__ . '/' . $logFile;

function writeLog($message) {
    global $logPath;
    $timestamp = date('Y-m-d H:i:s');
    @file_put_contents($logPath, "[$timestamp] $message\n", FILE_APPEND);
    error_log($message); // Também envia para error_log do PHP
}

// Tentar criar log
try {
    writeLog("=== INÍCIO - gravarProva2_LOG_v2.php ===");
    writeLog("PHP Version: " . phpversion());
    writeLog("Script: " . __FILE__);
    writeLog("Method: " . $_SERVER['REQUEST_METHOD']);

    // Verificar se conseguimos escrever
    if (!file_exists($logPath)) {
        echo json_encode(["error" => "Não foi possível criar arquivo de log", "path" => $logPath]);
        exit;
    }

    writeLog("✅ Arquivo de log criado com sucesso");

} catch (Exception $e) {
    echo json_encode(["error" => "Erro ao criar log: " . $e->getMessage()]);
    exit;
}

// ============================================================
// SESSÃO
// ============================================================
try {
    session_start();
    writeLog("Session started");
    writeLog("Session ID: " . session_id());
    writeLog("Usuario: " . (isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : "NÃO DEFINIDO"));
    writeLog("Tipo: " . (isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "NÃO DEFINIDO"));
    writeLog("ID: " . (isset($_SESSION["id"]) ? $_SESSION["id"] : "NÃO DEFINIDO"));

    if (!isset($_SESSION["usuario"])) {
        writeLog("❌ ERRO: Sessão não tem usuario");
        echo json_encode(["error" => "Usuário não autenticado", "log" => $logFile]);
        exit;
    }

    if ($_SESSION["tipo"] != "professor") {
        writeLog("❌ ERRO: Tipo não é professor: " . $_SESSION["tipo"]);
        echo json_encode(["error" => "Acesso negado - tipo: " . $_SESSION["tipo"], "log" => $logFile]);
        exit;
    }

    writeLog("✅ Sessão validada");

} catch (Exception $e) {
    writeLog("❌ ERRO na sessão: " . $e->getMessage());
    echo json_encode(["error" => "Erro na sessão: " . $e->getMessage(), "log" => $logFile]);
    exit;
}

// ============================================================
// DADOS RECEBIDOS
// ============================================================
try {
    writeLog("\n--- DADOS RECEBIDOS ---");
    writeLog("POST count: " . count($_POST));
    writeLog("POST keys: " . implode(", ", array_keys($_POST)));

    foreach ($_POST as $key => $value) {
        $valueStr = is_string($value) ? substr($value, 0, 100) : $value;
        writeLog("POST[$key] = $valueStr");
    }

    writeLog("FILES count: " . count($_FILES));
    if (!empty($_FILES)) {
        foreach ($_FILES as $key => $file) {
            writeLog("FILE[$key] = " . $file['name'] . " (" . $file['size'] . " bytes)");
        }
    }

    $data = $_POST;

} catch (Exception $e) {
    writeLog("❌ ERRO ao processar dados: " . $e->getMessage());
    echo json_encode(["error" => "Erro ao processar dados: " . $e->getMessage(), "log" => $logFile]);
    exit;
}

// ============================================================
// INCLUIR CONEXÃO
// ============================================================
try {
    writeLog("\n--- INCLUIR CONEXAO.PHP ---");

    if (!file_exists('conexao.php')) {
        writeLog("❌ ERRO: conexao.php não existe");
        echo json_encode(["error" => "Arquivo conexao.php não encontrado", "log" => $logFile]);
        exit;
    }

    writeLog("Incluindo conexao.php...");
    include 'conexao.php';
    writeLog("✅ conexao.php incluído");

    if (!class_exists('Seguranca')) {
        writeLog("❌ ERRO: Classe Seguranca não existe");
        echo json_encode(["error" => "Classe Seguranca não encontrada", "log" => $logFile]);
        exit;
    }

    writeLog("✅ Classe Seguranca existe");
    $seguranca = new Seguranca();
    $idProfessor = $_SESSION["id"];
    writeLog("ID Professor: " . $idProfessor);

} catch (Exception $e) {
    writeLog("❌ ERRO ao incluir conexao: " . $e->getMessage());
    echo json_encode(["error" => "Erro ao incluir conexao: " . $e->getMessage(), "log" => $logFile]);
    exit;
}

// ============================================================
// VALIDAÇÃO
// ============================================================
try {
    writeLog("\n--- VALIDAÇÃO ---");

    if (!isset($data["titulo"]) || trim($data["titulo"]) == "") {
        writeLog("❌ ERRO: Título vazio");
        echo json_encode(["error" => "Título obrigatório", "log" => $logFile]);
        exit;
    }
    writeLog("✅ Título: " . $data["titulo"]);

    if (!isset($data["descricao"]) || trim($data["descricao"]) == "") {
        writeLog("❌ ERRO: Descrição vazia");
        echo json_encode(["error" => "Descrição obrigatória", "log" => $logFile]);
        exit;
    }
    writeLog("✅ Descrição: " . substr($data["descricao"], 0, 50));

} catch (Exception $e) {
    writeLog("❌ ERRO na validação: " . $e->getMessage());
    echo json_encode(["error" => "Erro na validação: " . $e->getMessage(), "log" => $logFile]);
    exit;
}

// ============================================================
// SANITIZAÇÃO
// ============================================================
try {
    writeLog("\n--- SANITIZAÇÃO ---");

    $titulo = $seguranca->antisql($data["titulo"]);
    $descricao = $seguranca->antisql($data["descricao"]);
    $correta = isset($data["correta"]) ? $seguranca->antisql($data["correta"]) : false;
    $idProva = isset($data["idProva"]) && $data["idProva"] && $data["idProva"] !== 'false' ? $seguranca->antisql($data["idProva"]) : false;
    $tipo = isset($data["tipo"]) ? $seguranca->antisql($data["tipo"]) : 'objetiva';
    $peso = isset($data["peso"]) ? str_replace(",", ".", $seguranca->antisql($data["peso"])) : 1;

    writeLog("Título: $titulo");
    writeLog("Tipo: $tipo");
    writeLog("Peso: $peso");
    writeLog("Correta: " . ($correta !== false ? $correta : "false"));
    writeLog("idProva passado: " . ($idProva ? $idProva : "false"));

    if (!is_numeric($peso) || $peso < 0 || $peso > 10) {
        $peso = 1;
    }

} catch (Exception $e) {
    writeLog("❌ ERRO na sanitização: " . $e->getMessage());
    echo json_encode(["error" => "Erro na sanitização: " . $e->getMessage(), "log" => $logFile]);
    exit;
}

// ============================================================
// GERENCIAR PROVA
// ============================================================
try {
    writeLog("\n--- GERENCIAMENTO DA PROVA ---");

    if (isset($_SESSION['idProva']) && $_SESSION['idProva'] && $idProva) {
        $idProva = $_SESSION['idProva'];
        writeLog("✅ Usando prova da sessão: $idProva");
    } else {
        writeLog("Criando nova prova...");

        $queryInsertProva = "INSERT INTO prova VALUES (NULL, '$titulo', '$idProfessor')";
        writeLog("Query: $queryInsertProva");

        $resultInsertProva = mysql_query($queryInsertProva);

        if (!$resultInsertProva) {
            $erro = mysql_error();
            writeLog("❌ ERRO INSERT prova: $erro");
            echo json_encode(["error" => "Erro INSERT prova: $erro", "log" => $logFile]);
            exit;
        }

        writeLog("✅ Prova inserida");

        $querySelectProva = "SELECT idProva FROM prova WHERE titulo = '$titulo' AND idProfessor = '$idProfessor' ORDER BY idProva DESC LIMIT 1";
        writeLog("Query: $querySelectProva");

        $resultSelectProva = mysql_query($querySelectProva);

        if (!$resultSelectProva || mysql_num_rows($resultSelectProva) == 0) {
            writeLog("❌ ERRO: Prova não encontrada após INSERT");
            echo json_encode(["error" => "Prova não encontrada após INSERT", "log" => $logFile]);
            exit;
        }

        $idProva = mysql_result($resultSelectProva, 0, 'idProva');
        $_SESSION['idProva'] = $idProva;
        writeLog("✅ ID prova: $idProva (salvo na sessão)");
    }

} catch (Exception $e) {
    writeLog("❌ ERRO ao gerenciar prova: " . $e->getMessage());
    echo json_encode(["error" => "Erro ao gerenciar prova: " . $e->getMessage(), "log" => $logFile]);
    exit;
}

// ============================================================
// ALTERNATIVAS
// ============================================================
try {
    writeLog("\n--- ALTERNATIVAS ---");

    $alternativas = [];
    for ($i = 0; $i <= 7; $i++) {
        if (isset($data[$i]) && trim($data[$i]) != "") {
            $alternativas[$i] = $seguranca->antisql($data[$i]);
            writeLog("Alt $i: " . substr($alternativas[$i], 0, 30));
        }
    }

    writeLog("Total alternativas: " . count($alternativas));

    if ($tipo == "objetiva") {
        if (count($alternativas) < 2) {
            writeLog("❌ ERRO: Menos de 2 alternativas");
            echo json_encode(["error" => "Mínimo 2 alternativas", "log" => $logFile]);
            exit;
        }

        if ($correta === false || !isset($alternativas[$correta])) {
            writeLog("❌ ERRO: Alternativa correta inválida");
            echo json_encode(["error" => "Selecione alternativa correta", "log" => $logFile]);
            exit;
        }

        writeLog("✅ Validação objetiva OK");
    }

} catch (Exception $e) {
    writeLog("❌ ERRO nas alternativas: " . $e->getMessage());
    echo json_encode(["error" => "Erro nas alternativas: " . $e->getMessage(), "log" => $logFile]);
    exit;
}

// ============================================================
// INSERIR QUESTÃO
// ============================================================
try {
    writeLog("\n--- INSERIR QUESTÃO ---");

    $queryInsertQuestao = "INSERT INTO questao2 VALUES (NULL, '$descricao', '$tipo', '$peso', '$idProva', NULL)";
    writeLog("Query: $queryInsertQuestao");

    $resultInsertQuestao = mysql_query($queryInsertQuestao);

    if (!$resultInsertQuestao) {
        $erro = mysql_error();
        writeLog("❌ ERRO INSERT questão: $erro");
        echo json_encode(["error" => "Erro INSERT questão: $erro", "log" => $logFile]);
        exit;
    }

    writeLog("✅ Questão inserida");

    $querySelectQuestao = "SELECT idQuestao FROM questao2 WHERE descricao = '$descricao' AND tipo = '$tipo' AND idProva = '$idProva' ORDER BY idQuestao DESC LIMIT 1";
    writeLog("Query: $querySelectQuestao");

    $resultSelectQuestao = mysql_query($querySelectQuestao);

    if (!$resultSelectQuestao || mysql_num_rows($resultSelectQuestao) == 0) {
        writeLog("❌ ERRO: Questão não encontrada após INSERT");
        echo json_encode(["error" => "Questão não encontrada após INSERT", "log" => $logFile]);
        exit;
    }

    $idQuestao = mysql_result($resultSelectQuestao, 0, 'idQuestao');
    writeLog("✅ ID questão: $idQuestao");

} catch (Exception $e) {
    writeLog("❌ ERRO ao inserir questão: " . $e->getMessage());
    echo json_encode(["error" => "Erro ao inserir questão: " . $e->getMessage(), "log" => $logFile]);
    exit;
}

// ============================================================
// INSERIR ALTERNATIVAS
// ============================================================
try {
    writeLog("\n--- INSERIR ALTERNATIVAS ---");

    if ($tipo == "objetiva") {
        foreach ($alternativas as $index => $textoAlternativa) {
            $altCorreta = ($correta == $index) ? "sim" : "nao";

            $queryInsertAlt = "INSERT INTO alternativa VALUES (NULL, '$textoAlternativa', '$altCorreta', '$idQuestao')";
            writeLog("Query alt $index: $queryInsertAlt");

            $resultInsertAlt = mysql_query($queryInsertAlt);

            if (!$resultInsertAlt) {
                writeLog("❌ ERRO INSERT alt $index: " . mysql_error());
            } else {
                writeLog("✅ Alt $index inserida");
            }
        }
    }

} catch (Exception $e) {
    writeLog("❌ ERRO ao inserir alternativas: " . $e->getMessage());
    // Não bloqueia o sucesso
}

// ============================================================
// SUCESSO
// ============================================================
writeLog("\n=== ✅✅✅ SUCESSO TOTAL ===");
writeLog("ID Prova: $idProva");
writeLog("ID Questão: $idQuestao");

echo json_encode([
    "success" => true,
    "idProva" => $idProva,
    "idQuestao" => $idQuestao,
    "log" => $logFile
]);

writeLog("=== FIM ===\n\n");
?>
