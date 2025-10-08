<?php
/**
 * Script para identificar arquivos órfãos no sistema
 * Arquivos órfãos = existem no servidor mas não têm referência no banco de dados
 */

include 'conexao.php';

// Arrays para armazenar resultados
$arquivos_orfaos = [];
$estatisticas = [
    'atividades' => ['total' => 0, 'orfaos' => 0],
    'correcao' => ['total' => 0, 'orfaos' => 0],
    'arquivos' => ['total' => 0, 'orfaos' => 0]
];

echo "<h2>Verificação de Arquivos Órfãos</h2>";
echo "<pre>";
echo "========================================\n";
echo "Iniciando verificação...\n\n";

// 1. VERIFICAR PASTA /atividades/
echo "1. Verificando pasta /atividades/\n";
echo "-----------------------------------\n";

$pasta_atividades = 'atividades/';
if (is_dir($pasta_atividades)) {
    $arquivos = scandir($pasta_atividades);

    foreach ($arquivos as $arquivo) {
        if ($arquivo != '.' && $arquivo != '..') {
            $estatisticas['atividades']['total']++;
            $caminho_completo = $pasta_atividades . $arquivo;

            // Verificar se existe no banco
            $sql = "SELECT COUNT(*) as total FROM atividade WHERE arquivo LIKE '%$arquivo%'";
            $result = mysql_query($sql);
            $row = mysql_fetch_assoc($result);

            if ($row['total'] == 0) {
                $estatisticas['atividades']['orfaos']++;
                $tamanho = filesize($caminho_completo);
                $arquivos_orfaos['atividades'][] = [
                    'arquivo' => $arquivo,
                    'caminho' => $caminho_completo,
                    'tamanho' => number_format($tamanho / 1024, 2) . ' KB',
                    'data_modificacao' => date("d/m/Y H:i", filemtime($caminho_completo))
                ];
                echo "  ❌ ÓRFÃO: $arquivo (" . number_format($tamanho / 1024, 2) . " KB)\n";
            } else {
                echo "  ✅ OK: $arquivo\n";
            }
        }
    }
}

// 2. VERIFICAR PASTA /correcao/
echo "\n2. Verificando pasta /correcao/\n";
echo "-----------------------------------\n";

$pasta_correcao = 'correcao/';
if (is_dir($pasta_correcao)) {
    $arquivos = scandir($pasta_correcao);

    foreach ($arquivos as $arquivo) {
        if ($arquivo != '.' && $arquivo != '..') {
            $estatisticas['correcao']['total']++;
            $caminho_completo = $pasta_correcao . $arquivo;

            // Verificar se existe no banco (campo arquivo_correcao)
            $sql = "SELECT COUNT(*) as total FROM atividade WHERE arquivo_correcao LIKE '%$arquivo%'";
            $result = mysql_query($sql);
            $row = mysql_fetch_assoc($result);

            if ($row['total'] == 0) {
                $estatisticas['correcao']['orfaos']++;
                $tamanho = filesize($caminho_completo);
                $arquivos_orfaos['correcao'][] = [
                    'arquivo' => $arquivo,
                    'caminho' => $caminho_completo,
                    'tamanho' => number_format($tamanho / 1024, 2) . ' KB',
                    'data_modificacao' => date("d/m/Y H:i", filemtime($caminho_completo))
                ];
                echo "  ❌ ÓRFÃO: $arquivo (" . number_format($tamanho / 1024, 2) . " KB)\n";
            } else {
                echo "  ✅ OK: $arquivo\n";
            }
        }
    }
}

// 3. VERIFICAR PASTA /arquivos/
echo "\n3. Verificando pasta /arquivos/\n";
echo "-----------------------------------\n";

$pasta_arquivos = 'arquivos/';
if (is_dir($pasta_arquivos)) {
    $arquivos = scandir($pasta_arquivos);

    foreach ($arquivos as $arquivo) {
        if ($arquivo != '.' && $arquivo != '..') {
            $estatisticas['arquivos']['total']++;
            $caminho_completo = $pasta_arquivos . $arquivo;

            // Verificar se existe no banco (tabela conteudo)
            $sql = "SELECT COUNT(*) as total FROM conteudo WHERE arquivo LIKE '%$arquivo%'";
            $result = mysql_query($sql);
            $row = mysql_fetch_assoc($result);

            if ($row['total'] == 0) {
                $estatisticas['arquivos']['orfaos']++;
                $tamanho = filesize($caminho_completo);
                $arquivos_orfaos['arquivos'][] = [
                    'arquivo' => $arquivo,
                    'caminho' => $caminho_completo,
                    'tamanho' => number_format($tamanho / 1024, 2) . ' KB',
                    'data_modificacao' => date("d/m/Y H:i", filemtime($caminho_completo))
                ];
                echo "  ❌ ÓRFÃO: $arquivo (" . number_format($tamanho / 1024, 2) . " KB)\n";
            } else {
                echo "  ✅ OK: $arquivo\n";
            }
        }
    }
}

// RESUMO FINAL
echo "\n========================================\n";
echo "RESUMO DA VERIFICAÇÃO\n";
echo "========================================\n\n";

$total_orfaos = 0;
$espaco_desperdicado = 0;

foreach ($estatisticas as $pasta => $stats) {
    echo "📁 /$pasta/\n";
    echo "   Total de arquivos: " . $stats['total'] . "\n";
    echo "   Arquivos órfãos: " . $stats['orfaos'] . "\n";
    echo "   Arquivos válidos: " . ($stats['total'] - $stats['orfaos']) . "\n\n";
    $total_orfaos += $stats['orfaos'];
}

// Calcular espaço total desperdiçado
foreach ($arquivos_orfaos as $pasta => $arquivos) {
    foreach ($arquivos as $arquivo) {
        $tamanho_kb = floatval(str_replace(' KB', '', str_replace(',', '.', $arquivo['tamanho'])));
        $espaco_desperdicado += $tamanho_kb;
    }
}

echo "----------------------------------------\n";
echo "📊 TOTAIS GERAIS:\n";
echo "   Total de arquivos órfãos: $total_orfaos\n";
echo "   Espaço desperdiçado: " . number_format($espaco_desperdicado / 1024, 2) . " MB\n";
echo "========================================\n";

// OPÇÃO PARA EXPORTAR LISTA
if ($total_orfaos > 0) {
    echo "\n📝 LISTA DETALHADA DE ARQUIVOS ÓRFÃOS:\n";
    echo "========================================\n\n";

    foreach ($arquivos_orfaos as $pasta => $arquivos) {
        if (count($arquivos) > 0) {
            echo "📁 Pasta: /$pasta/\n";
            echo "----------------------------------------\n";
            foreach ($arquivos as $arquivo) {
                echo "Arquivo: " . $arquivo['arquivo'] . "\n";
                echo "Tamanho: " . $arquivo['tamanho'] . "\n";
                echo "Última modificação: " . $arquivo['data_modificacao'] . "\n";
                echo "Caminho: " . $arquivo['caminho'] . "\n";
                echo "----------------------------------------\n";
            }
            echo "\n";
        }
    }

    // Criar arquivo de log
    $log_content = "RELATÓRIO DE ARQUIVOS ÓRFÃOS - " . date('d/m/Y H:i:s') . "\n\n";
    foreach ($arquivos_orfaos as $pasta => $arquivos) {
        foreach ($arquivos as $arquivo) {
            $log_content .= $arquivo['caminho'] . "\n";
        }
    }

    $log_file = 'arquivos_orfaos_' . date('Ymd_His') . '.txt';
    file_put_contents($log_file, $log_content);
    echo "\n✅ Log salvo em: $log_file\n";

    echo "\n⚠️ ATENÇÃO: Estes arquivos podem ser removidos com segurança!\n";
    echo "   Eles não têm referência no banco de dados.\n";
}

echo "</pre>";

// Botão para baixar o relatório
if ($total_orfaos > 0) {
    echo "<br><a href='$log_file' download>📥 Baixar relatório de arquivos órfãos</a>";
}

mysql_close($conexao);
?>