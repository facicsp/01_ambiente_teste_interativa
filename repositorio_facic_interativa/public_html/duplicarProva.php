<?php
/**
 * ARQUIVO: duplicarProva.php
 * FUNÇÃO: Duplicar prova completa (questões + alternativas)
 * VERSÃO: 2.0 - CORRIGIDA E MELHORADA
 * DATA: 01/10/2025
 *
 * CORREÇÕES APLICADAS:
 * - Removido ponto-e-vírgula incorreto dentro das queries SQL
 * - Adicionado tratamento de erros robusto
 * - Implementado feedback de sucesso/erro para o usuário
 * - Melhorada legibilidade e documentação do código
 *
 * CHANGELOG:
 * v1.0 - Versão original (com bug SQL)
 * v2.0 - Correção de sintaxe SQL + tratamento de erros
 */

session_start();
include './conexao.php';

// ============================================================
// VALIDAÇÃO DE ACESSO
// ============================================================

if (!isset($_SESSION["usuario"]) || $_SESSION["tipo"] != "professor") {
    exit("<script>alert('Acesso negado!'); window.location='login.html';</script>");
}

// ============================================================
// VALIDAÇÃO DE PARÂMETROS
// ============================================================

if (!isset($_GET['id']) || trim($_GET['id']) == '') {
    exit("<script>alert('ID da prova não fornecido!'); window.location='visualizarProvas2.php';</script>");
}

$seguranca = new Seguranca();
$_idProva = $seguranca->antisql($_GET['id']);

// ============================================================
// BUSCAR PROVA ORIGINAL
// ============================================================

$queryProva = "SELECT * FROM prova WHERE idProva = '$_idProva'";
$resultProva = mysql_query($queryProva);

if (!$resultProva) {
    exit("<script>alert('Erro ao buscar prova: " . addslashes(mysql_error()) . "'); window.location='visualizarProvas2.php';</script>");
}

if (mysql_num_rows($resultProva) == 0) {
    exit("<script>alert('Prova não encontrada!'); window.location='visualizarProvas2.php';</script>");
}

$titulo = mysql_result($resultProva, 0, 'titulo');
$idProfessor = mysql_result($resultProva, 0, 'idProfessor');

// Verificar se o professor é o dono da prova
if ($idProfessor != $_SESSION['id']) {
    exit("<script>alert('Você não tem permissão para duplicar esta prova!'); window.location='visualizarProvas2.php';</script>");
}

// ============================================================
// CRIAR NOVA PROVA (CÓPIA)
// ============================================================

$novoTitulo = $seguranca->antisql($titulo . " - cópia");

// ✅ CORRIGIDO: Removido ; de dentro da query
$queryInsertProva = "INSERT INTO prova VALUES (NULL, '$novoTitulo', '$idProfessor')";
$resultInsertProva = mysql_query($queryInsertProva);

if (!$resultInsertProva) {
    exit("<script>alert('Erro ao criar cópia da prova: " . addslashes(mysql_error()) . "'); window.location='visualizarProvas2.php';</script>");
}

// Recuperar ID da nova prova
$querySelectProva = "SELECT idProva FROM prova WHERE idProfessor = '$idProfessor' ORDER BY idProva DESC LIMIT 1";
$resultSelectProva = mysql_query($querySelectProva);

if (!$resultSelectProva || mysql_num_rows($resultSelectProva) == 0) {
    exit("<script>alert('Erro ao recuperar ID da nova prova!'); window.location='visualizarProvas2.php';</script>");
}

$idProva = mysql_result($resultSelectProva, 0, 'idProva');

// ============================================================
// DUPLICAR QUESTÕES
// ============================================================

$queryQuestoes = "SELECT * FROM questao2 WHERE idProva = '$_idProva'";
$resultQuestoes = mysql_query($queryQuestoes);

if (!$resultQuestoes) {
    // Erro ao buscar questões - excluir prova vazia criada
    mysql_query("DELETE FROM prova WHERE idProva = '$idProva'");
    exit("<script>alert('Erro ao buscar questões: " . addslashes(mysql_error()) . "'); window.location='visualizarProvas2.php';</script>");
}

$totalQuestoes = mysql_num_rows($resultQuestoes);

if ($totalQuestoes == 0) {
    // Prova sem questões - excluir e avisar
    mysql_query("DELETE FROM prova WHERE idProva = '$idProva'");
    exit("<script>alert('A prova original não possui questões para duplicar!'); window.location='visualizarProvas2.php';</script>");
}

$questoesCriadas = 0;
$alternativasCriadas = 0;

for ($i = 0; $i < $totalQuestoes; $i++) {
    $descricao = $seguranca->antisql(mysql_result($resultQuestoes, $i, 'descricao'));
    $idQuestaoOriginal = mysql_result($resultQuestoes, $i, 'idQuestao');
    $tipo = mysql_result($resultQuestoes, $i, 'tipo');
    $peso = mysql_result($resultQuestoes, $i, 'peso');

    // ✅ CORRIGIDO: Incluída coluna 'anexo' (estava faltando)
    // Buscar anexo da questão original
    $anexo = mysql_result($resultQuestoes, $i, 'anexo');
    $anexo = $anexo ? $seguranca->antisql($anexo) : NULL;

    $queryInsertQuestao = "INSERT INTO questao2 VALUES (NULL, '$descricao', '$tipo', '$peso', '$idProva', " . ($anexo ? "'$anexo'" : "NULL") . ")";
    $resultInsertQuestao = mysql_query($queryInsertQuestao);

    if (!$resultInsertQuestao) {
        // Log do erro mas continua tentando duplicar outras questões
        error_log("Erro ao duplicar questão $idQuestaoOriginal: " . mysql_error());
        continue;
    }

    // Recuperar ID da questão criada
    $querySelectQuestao = "SELECT idQuestao FROM questao2 WHERE idProva = '$idProva' ORDER BY idQuestao DESC LIMIT 1";
    $resultSelectQuestao = mysql_query($querySelectQuestao);

    if (!$resultSelectQuestao || mysql_num_rows($resultSelectQuestao) == 0) {
        error_log("Erro ao recuperar ID da questão duplicada");
        continue;
    }

    $idQuestaoNova = mysql_result($resultSelectQuestao, 0, 'idQuestao');
    $questoesCriadas++;

    // ============================================================
    // DUPLICAR ALTERNATIVAS (SE QUESTÃO OBJETIVA)
    // ============================================================

    if ($tipo == 'objetiva') {
        $queryAlternativas = "SELECT * FROM alternativa WHERE idQuestao = '$idQuestaoOriginal'";
        $resultAlternativas = mysql_query($queryAlternativas);

        if ($resultAlternativas) {
            $totalAlternativas = mysql_num_rows($resultAlternativas);

            for ($ialt = 0; $ialt < $totalAlternativas; $ialt++) {
                $alternativa = $seguranca->antisql(mysql_result($resultAlternativas, $ialt, 'alternativa'));
                $correta = mysql_result($resultAlternativas, $ialt, 'correta');

                // ✅ CORRIGIDO: Removido ; de dentro da query
                $queryInsertAlt = "INSERT INTO alternativa VALUES (NULL, '$alternativa', '$correta', '$idQuestaoNova')";
                $resultInsertAlt = mysql_query($queryInsertAlt);

                if ($resultInsertAlt) {
                    $alternativasCriadas++;
                } else {
                    error_log("Erro ao duplicar alternativa: " . mysql_error());
                }
            }
        } else {
            error_log("Erro ao buscar alternativas da questão $idQuestaoOriginal: " . mysql_error());
        }
    }
}

// ============================================================
// VALIDAÇÃO FINAL E FEEDBACK
// ============================================================

if ($questoesCriadas == 0) {
    // Nenhuma questão foi duplicada - excluir prova vazia
    mysql_query("DELETE FROM prova WHERE idProva = '$idProva'");
    exit("<script>alert('Erro: Nenhuma questão foi duplicada!'); window.location='visualizarProvas2.php';</script>");
}

// Sucesso!
$mensagem = "Prova duplicada com sucesso!\\n\\n";
$mensagem .= "Título: $novoTitulo\\n";
$mensagem .= "ID: $idProva\\n";
$mensagem .= "Questões duplicadas: $questoesCriadas de $totalQuestoes\\n";

if ($alternativasCriadas > 0) {
    $mensagem .= "Alternativas duplicadas: $alternativasCriadas";
}

exit("<script>alert('$mensagem'); window.location='visualizarProvas2.php';</script>");

?>
