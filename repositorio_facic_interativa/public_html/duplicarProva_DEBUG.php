<?php
/**
 * VERSÃO DEBUG - Para diagnosticar problema de duplicação
 * USE ESTA URL: http://www.facicinterativa.com.br/ambiente_QA/duplicarProva_DEBUG.php?id=14770
 */

session_start();
include './conexao.php';

// Habilitar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Debug Duplicação</title>";
echo "<style>body{font-family:monospace;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;} pre{background:#f0f0f0;padding:10px;border-radius:5px;}</style>";
echo "</head><body>";
echo "<h1>DEBUG - Duplicação de Prova</h1>";

// Validações básicas
if (!isset($_SESSION["usuario"]) || $_SESSION["tipo"] != "professor") {
    die("<p class='error'>❌ Acesso negado! Usuário não autenticado ou não é professor.</p>");
}

echo "<p class='success'>✅ Sessão válida: " . $_SESSION["usuario"] . " (ID: " . $_SESSION["id"] . ")</p>";

if (!isset($_GET['id']) || trim($_GET['id']) == '') {
    die("<p class='error'>❌ ID da prova não fornecido!</p>");
}

$seguranca = new Seguranca();
$_idProva = $seguranca->antisql($_GET['id']);

echo "<p class='info'>📋 ID da prova a duplicar: <strong>$_idProva</strong></p>";

// ============================================================
// 1. BUSCAR PROVA ORIGINAL
// ============================================================

echo "<hr><h2>1️⃣ BUSCAR PROVA ORIGINAL</h2>";

$queryProva = "SELECT * FROM prova WHERE idProva = '$_idProva'";
echo "<pre>Query: $queryProva</pre>";

$resultProva = mysql_query($queryProva);

if (!$resultProva) {
    die("<p class='error'>❌ Erro ao buscar prova: " . mysql_error() . "</p>");
}

$numRows = mysql_num_rows($resultProva);
echo "<p>Linhas retornadas: <strong>$numRows</strong></p>";

if ($numRows == 0) {
    die("<p class='error'>❌ Prova não encontrada no banco!</p>");
}

$titulo = mysql_result($resultProva, 0, 'titulo');
$idProfessor = mysql_result($resultProva, 0, 'idProfessor');

echo "<p class='success'>✅ Prova encontrada!</p>";
echo "<ul>";
echo "<li><strong>Título:</strong> $titulo</li>";
echo "<li><strong>ID Professor:</strong> $idProfessor</li>";
echo "</ul>";

// Verificar permissão
if ($idProfessor != $_SESSION['id']) {
    die("<p class='error'>❌ Você (ID: {$_SESSION['id']}) não tem permissão para duplicar esta prova (dono: $idProfessor)!</p>");
}

echo "<p class='success'>✅ Permissão validada</p>";

// ============================================================
// 2. VERIFICAR QUESTÕES DA PROVA ORIGINAL
// ============================================================

echo "<hr><h2>2️⃣ VERIFICAR QUESTÕES DA PROVA ORIGINAL</h2>";

$queryQuestoes = "SELECT * FROM questao2 WHERE idProva = '$_idProva'";
echo "<pre>Query: $queryQuestoes</pre>";

$resultQuestoes = mysql_query($queryQuestoes);

if (!$resultQuestoes) {
    die("<p class='error'>❌ Erro ao buscar questões: " . mysql_error() . "</p>");
}

$totalQuestoes = mysql_num_rows($resultQuestoes);
echo "<p>Questões encontradas: <strong>$totalQuestoes</strong></p>";

if ($totalQuestoes == 0) {
    die("<p class='error'>❌ A prova original NÃO possui questões para duplicar!</p>");
}

echo "<p class='success'>✅ Prova possui $totalQuestoes questões</p>";

// Mostrar detalhes das questões
echo "<h3>Detalhes das Questões:</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>ID Questão</th><th>Tipo</th><th>Peso</th><th>Descrição (primeiros 50 chars)</th></tr>";

for ($i = 0; $i < $totalQuestoes; $i++) {
    $idQuestao = mysql_result($resultQuestoes, $i, 'idQuestao');
    $tipo = mysql_result($resultQuestoes, $i, 'tipo');
    $peso = mysql_result($resultQuestoes, $i, 'peso');
    $descricao = mysql_result($resultQuestoes, $i, 'descricao');
    $descricaoPreview = substr($descricao, 0, 50) . (strlen($descricao) > 50 ? '...' : '');

    echo "<tr>";
    echo "<td>$idQuestao</td>";
    echo "<td>$tipo</td>";
    echo "<td>$peso</td>";
    echo "<td>$descricaoPreview</td>";
    echo "</tr>";

    // Para questões objetivas, contar alternativas
    if ($tipo == 'objetiva') {
        $queryAlt = "SELECT COUNT(*) as total FROM alternativa WHERE idQuestao = '$idQuestao'";
        $resultAlt = mysql_query($queryAlt);
        $totalAlt = mysql_result($resultAlt, 0, 'total');
        echo "<tr><td colspan='4' style='background:#f9f9f9;'>&nbsp;&nbsp;&nbsp;↳ $totalAlt alternativas</td></tr>";
    }
}

echo "</table>";

// ============================================================
// 3. CRIAR NOVA PROVA (CÓPIA)
// ============================================================

echo "<hr><h2>3️⃣ CRIAR NOVA PROVA (CÓPIA)</h2>";

$novoTitulo = $seguranca->antisql($titulo . " - cópia DEBUG " . date('His'));

$queryInsertProva = "INSERT INTO prova VALUES (NULL, '$novoTitulo', '$idProfessor')";
echo "<pre>Query: $queryInsertProva</pre>";

$resultInsertProva = mysql_query($queryInsertProva);

if (!$resultInsertProva) {
    die("<p class='error'>❌ Erro ao criar cópia da prova: " . mysql_error() . "</p>");
}

echo "<p class='success'>✅ Prova criada com sucesso!</p>";

// Recuperar ID da nova prova
$querySelectProva = "SELECT idProva FROM prova WHERE idProfessor = '$idProfessor' ORDER BY idProva DESC LIMIT 1";
$resultSelectProva = mysql_query($querySelectProva);

if (!$resultSelectProva || mysql_num_rows($resultSelectProva) == 0) {
    die("<p class='error'>❌ Erro ao recuperar ID da nova prova!</p>");
}

$idProva = mysql_result($resultSelectProva, 0, 'idProva');
echo "<p class='info'>🆔 Nova prova criada com ID: <strong>$idProva</strong></p>";

// ============================================================
// 4. DUPLICAR QUESTÕES
// ============================================================

echo "<hr><h2>4️⃣ DUPLICAR QUESTÕES</h2>";

$questoesCriadas = 0;
$alternativasCriadas = 0;
$erros = [];

for ($i = 0; $i < $totalQuestoes; $i++) {
    echo "<h3>Questão " . ($i + 1) . " de $totalQuestoes</h3>";

    $descricao = $seguranca->antisql(mysql_result($resultQuestoes, $i, 'descricao'));
    $idQuestaoOriginal = mysql_result($resultQuestoes, $i, 'idQuestao');
    $tipo = mysql_result($resultQuestoes, $i, 'tipo');
    $peso = mysql_result($resultQuestoes, $i, 'peso');
    $anexo = mysql_result($resultQuestoes, $i, 'anexo');
    $anexo = $anexo ? $seguranca->antisql($anexo) : NULL;

    echo "<p>ID Original: $idQuestaoOriginal | Tipo: $tipo | Peso: $peso | Anexo: " . ($anexo ? $anexo : 'NULL') . "</p>";

    $queryInsertQuestao = "INSERT INTO questao2 VALUES (NULL, '$descricao', '$tipo', '$peso', '$idProva', " . ($anexo ? "'$anexo'" : "NULL") . ")";
    echo "<pre>Query: " . substr($queryInsertQuestao, 0, 150) . "...</pre>";

    $resultInsertQuestao = mysql_query($queryInsertQuestao);

    if (!$resultInsertQuestao) {
        $erro = mysql_error();
        echo "<p class='error'>❌ ERRO ao inserir questão: $erro</p>";
        $erros[] = "Questão $idQuestaoOriginal: $erro";
        continue;
    }

    echo "<p class='success'>✅ Questão inserida</p>";

    // Recuperar ID da questão criada
    $querySelectQuestao = "SELECT idQuestao FROM questao2 WHERE idProva = '$idProva' ORDER BY idQuestao DESC LIMIT 1";
    $resultSelectQuestao = mysql_query($querySelectQuestao);

    if (!$resultSelectQuestao || mysql_num_rows($resultSelectQuestao) == 0) {
        echo "<p class='error'>❌ Erro ao recuperar ID da questão duplicada</p>";
        $erros[] = "Não recuperou ID da questão $idQuestaoOriginal";
        continue;
    }

    $idQuestaoNova = mysql_result($resultSelectQuestao, 0, 'idQuestao');
    echo "<p class='info'>🆔 Nova questão criada com ID: <strong>$idQuestaoNova</strong></p>";

    $questoesCriadas++;

    // Duplicar alternativas se for objetiva
    if ($tipo == 'objetiva') {
        echo "<h4>Duplicando Alternativas</h4>";

        $queryAlternativas = "SELECT * FROM alternativa WHERE idQuestao = '$idQuestaoOriginal'";
        $resultAlternativas = mysql_query($queryAlternativas);

        if (!$resultAlternativas) {
            echo "<p class='error'>❌ Erro ao buscar alternativas: " . mysql_error() . "</p>";
            continue;
        }

        $totalAlternativas = mysql_num_rows($resultAlternativas);
        echo "<p>Alternativas encontradas: $totalAlternativas</p>";

        for ($ialt = 0; $ialt < $totalAlternativas; $ialt++) {
            $alternativa = $seguranca->antisql(mysql_result($resultAlternativas, $ialt, 'alternativa'));
            $correta = mysql_result($resultAlternativas, $ialt, 'correta');

            $queryInsertAlt = "INSERT INTO alternativa VALUES (NULL, '$alternativa', '$correta', '$idQuestaoNova')";
            $resultInsertAlt = mysql_query($queryInsertAlt);

            if ($resultInsertAlt) {
                $alternativasCriadas++;
                echo "<p class='success'>✅ Alternativa " . ($ialt + 1) . " inserida (correta: $correta)</p>";
            } else {
                echo "<p class='error'>❌ Erro ao inserir alternativa: " . mysql_error() . "</p>";
            }
        }
    }

    echo "<hr style='border:1px dashed #ccc;'>";
}

// ============================================================
// 5. RESULTADO FINAL
// ============================================================

echo "<hr><h2>5️⃣ RESULTADO FINAL</h2>";

echo "<table border='1' cellpadding='10' cellspacing='0'>";
echo "<tr><th>Métrica</th><th>Valor</th></tr>";
echo "<tr><td>Questões originais</td><td><strong>$totalQuestoes</strong></td></tr>";
echo "<tr><td>Questões duplicadas</td><td><strong style='color:" . ($questoesCriadas > 0 ? 'green' : 'red') . "'>$questoesCriadas</strong></td></tr>";
echo "<tr><td>Alternativas duplicadas</td><td><strong>$alternativasCriadas</strong></td></tr>";
echo "<tr><td>Erros</td><td><strong style='color:red'>" . count($erros) . "</strong></td></tr>";
echo "</table>";

if (count($erros) > 0) {
    echo "<h3 style='color:red;'>⚠️ Erros Encontrados:</h3>";
    echo "<ul>";
    foreach ($erros as $erro) {
        echo "<li>$erro</li>";
    }
    echo "</ul>";
}

if ($questoesCriadas == 0) {
    echo "<p class='error'>❌ NENHUMA QUESTÃO FOI DUPLICADA!</p>";
    echo "<p>Excluindo prova vazia criada (ID: $idProva)...</p>";
    mysql_query("DELETE FROM prova WHERE idProva = '$idProva'");
    echo "<p class='success'>✅ Prova vazia excluída</p>";
} else {
    echo "<p class='success'>✅ DUPLICAÇÃO CONCLUÍDA COM SUCESSO!</p>";
    echo "<p>Nova prova ID: <strong>$idProva</strong></p>";
    echo "<p>Título: <strong>$novoTitulo</strong></p>";
    echo "<p><a href='visualizarProvas2.php'>← Voltar para lista de provas</a></p>";
    echo "<p><a href='visualizarProvas2.php?id=$idProva'>👁️ Visualizar prova duplicada</a></p>";
}

echo "</body></html>";

?>
