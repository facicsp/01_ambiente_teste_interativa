<?php
/**
 * ARQUIVO: gravarProva2.php
 * FUNÇÃO: Gravar questões de provas no banco de dados
 * VERSÃO: 2.0 - CORRIGIDA
 * DATA: 01/10/2025
 * 
 * CORREÇÕES APLICADAS:
 * - Substituído json_decode() por $_POST (fix FormData)
 * - Adicionadas validações de dados
 * - Implementado upload de anexos
 * - Melhorado tratamento de erros
 * - Adicionados logs de debug (remover em produção)
 */

session_start();

// Verificar autenticação
if (!isset($_SESSION["usuario"]) || $_SESSION["tipo"] != "professor") {
    echo json_encode(["error" => "Acesso negado"]);
    exit;
}

// ✅ CORREÇÃO PRINCIPAL: Usar $_POST ao invés de json_decode
$data = $_POST;

// Incluir dependências
include 'conexao.php';

// Inicializar segurança
$seguranca = new Seguranca();
$idProfessor = $_SESSION["id"];

// ============================================================
// VALIDAÇÃO DE DADOS DE ENTRADA
// ============================================================

if (!isset($data["titulo"]) || trim($data["titulo"]) == "") {
    echo json_encode(["error" => "Título da prova é obrigatório"]);
    exit;
}

if (!isset($data["descricao"]) || trim($data["descricao"]) == "") {
    echo json_encode(["error" => "Descrição da questão é obrigatória"]);
    exit;
}

// ============================================================
// SANITIZAÇÃO DE DADOS
// ============================================================

$titulo = $seguranca->antisql($data["titulo"]);
$descricao = $seguranca->antisql($data["descricao"]);
$correta = isset($data["correta"]) ? $seguranca->antisql($data["correta"]) : false;
$idProva = isset($data["idProva"]) && $data["idProva"] ? $seguranca->antisql($data["idProva"]) : false;
$tipo = isset($data["tipo"]) ? $seguranca->antisql($data["tipo"]) : 'objetiva';
$peso = isset($data["peso"]) ? str_replace(",", ".", $seguranca->antisql($data["peso"])) : 1;

// Validar peso
if (!is_numeric($peso) || $peso < 0 || $peso > 10) {
    $peso = 1;
}

// ============================================================
// GERENCIAR PROVA (CRIAR OU USAR EXISTENTE)
// ============================================================

if (isset($_SESSION['idProva']) && $idProva) {
    // Usar prova existente da sessão
    $idProva = $_SESSION['idProva'];
} else {
    // Criar nova prova
    $queryInsertProva = "INSERT INTO prova VALUES (NULL, '$titulo', '$idProfessor')";
    $resultInsertProva = mysql_query($queryInsertProva);
    
    if (!$resultInsertProva) {
        echo json_encode(["error" => "Erro ao criar prova: " . mysql_error()]);
        exit;
    }
    
    // Recuperar ID da prova criada
    $querySelectProva = "SELECT idProva FROM prova 
                        WHERE titulo = '$titulo' 
                        AND idProfessor = '$idProfessor' 
                        ORDER BY idProva DESC LIMIT 1";
    $resultSelectProva = mysql_query($querySelectProva);
    
    if (!$resultSelectProva || mysql_num_rows($resultSelectProva) == 0) {
        echo json_encode(["error" => "Erro ao recuperar ID da prova"]);
        exit;
    }
    
    $idProva = mysql_result($resultSelectProva, 0, 'idProva');
    $_SESSION['idProva'] = $idProva;
}

// ============================================================
// PROCESSAR ALTERNATIVAS (PARA QUESTÕES OBJETIVAS)
// ============================================================

$alternativas = [];
for ($i = 0; $i <= 7; $i++) {
    if (isset($data[$i]) && trim($data[$i]) != "") {
        $alternativas[$i] = $seguranca->antisql($data[$i]);
    }
}

// Validar questões objetivas
if ($tipo == "objetiva") {
    if (count($alternativas) < 2) {
        echo json_encode(["error" => "Questão objetiva precisa ter pelo menos 2 alternativas"]);
        exit;
    }
    
    if ($correta === false || !isset($alternativas[$correta])) {
        echo json_encode(["error" => "Selecione a alternativa correta"]);
        exit;
    }
}

// ============================================================
// INSERIR QUESTÃO NO BANCO
// ============================================================

$queryInsertQuestao = "INSERT INTO questao2 VALUES (NULL, '$descricao', '$tipo', '$peso', '$idProva')";
$resultInsertQuestao = mysql_query($queryInsertQuestao);

if (!$resultInsertQuestao) {
    echo json_encode(["error" => "Erro ao criar questão: " . mysql_error()]);
    exit;
}

// Recuperar ID da questão criada
$querySelectQuestao = "SELECT idQuestao FROM questao2 
                      WHERE descricao = '$descricao' 
                      AND tipo = '$tipo' 
                      AND idProva = '$idProva' 
                      ORDER BY idQuestao DESC LIMIT 1";
$resultSelectQuestao = mysql_query($querySelectQuestao);

if (!$resultSelectQuestao || mysql_num_rows($resultSelectQuestao) == 0) {
    echo json_encode(["error" => "Erro ao recuperar ID da questão"]);
    exit;
}

$idQuestao = mysql_result($resultSelectQuestao, 0, 'idQuestao');

// ============================================================
// INSERIR ALTERNATIVAS (SE QUESTÃO OBJETIVA)
// ============================================================

if ($tipo == "objetiva") {
    foreach ($alternativas as $index => $textoAlternativa) {
        $altCorreta = ($correta == $index) ? "sim" : "nao";
        
        $queryInsertAlt = "INSERT INTO alternativa VALUES (NULL, '$textoAlternativa', '$altCorreta', '$idQuestao')";
        $resultInsertAlt = mysql_query($queryInsertAlt);
        
        if (!$resultInsertAlt) {
            // Log do erro mas continua processamento
            error_log("Erro ao inserir alternativa $index: " . mysql_error());
        }
    }
}

// ============================================================
// PROCESSAR UPLOAD DE ANEXO (OPCIONAL)
// ============================================================

if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/anexos/';
    
    // Criar diretório se não existir
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }
    
    $nomeOriginal = $_FILES['anexo']['name'];
    $caminhoTemporario = $_FILES['anexo']['tmp_name'];
    $tamanhoArquivo = $_FILES['anexo']['size'];
    $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
    
    // Validar extensão
    $extensoesPermitidas = ['pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx', 'txt'];
    
    // Validar tamanho (máximo 5MB)
    $tamanhoMaximo = 5 * 1024 * 1024; // 5MB em bytes
    
    if (!in_array($extensao, $extensoesPermitidas)) {
        // Arquivo com extensão não permitida (não bloqueia salvamento da questão)
        error_log("Anexo não salvo: extensão '$extensao' não permitida");
    } elseif ($tamanhoArquivo > $tamanhoMaximo) {
        // Arquivo muito grande (não bloqueia salvamento da questão)
        error_log("Anexo não salvo: tamanho excede 5MB");
    } else {
        // Gerar nome único para evitar conflitos
        $novoNome = uniqid('anexo_') . '_' . time() . '.' . $extensao;
        $caminhoDestino = $uploadDir . $novoNome;
        
        // Mover arquivo
        if (@move_uploaded_file($caminhoTemporario, $caminhoDestino)) {
            // Sucesso! 
            // TODO: Salvar referência no banco se necessário
            // Você pode adicionar uma coluna 'anexo' na tabela questao2
            // mysql_query("UPDATE questao2 SET anexo = '$caminhoDestino' WHERE idQuestao = '$idQuestao'");
        } else {
            error_log("Erro ao mover arquivo de upload para $caminhoDestino");
        }
    }
}

// ============================================================
// RETORNAR SUCESSO
// ============================================================

// Retornar ID da prova para o JavaScript continuar adicionando questões
echo json_encode($idProva);

?>
