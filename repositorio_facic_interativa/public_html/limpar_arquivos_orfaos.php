<?php
/**
 * Script para limpar arquivos órfãos com segurança
 * CUIDADO: Este script pode MOVER ou DELETAR arquivos!
 */

session_start();

// Verificar se é administrador
if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != "administrador") {
    die("Acesso negado! Apenas administradores podem executar este script.");
}

include 'conexao.php';

// Configurações
$MODO_TESTE = true; // Mudar para false para executar ações reais
$ACAO = 'mover'; // 'mover' ou 'deletar'
$PASTA_BACKUP = 'arquivos_orfaos_backup_' . date('Ymd_His') . '/';

// Processar formulário
if (isset($_POST['executar'])) {
    $MODO_TESTE = $_POST['modo'] == 'teste';
    $ACAO = $_POST['acao'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Limpeza de Arquivos Órfãos</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .aviso { background: #ffeb3b; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .sucesso { background: #4caf50; color: white; padding: 10px; border-radius: 5px; }
        .erro { background: #f44336; color: white; padding: 10px; border-radius: 5px; }
        .info { background: #2196f3; color: white; padding: 10px; border-radius: 5px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .orfao { background-color: #ffebee; }
        .valido { background-color: #e8f5e9; }
        button { padding: 10px 20px; margin: 5px; cursor: pointer; }
        .btn-danger { background: #f44336; color: white; border: none; }
        .btn-warning { background: #ff9800; color: white; border: none; }
        .btn-info { background: #2196f3; color: white; border: none; }
    </style>
</head>
<body>

<div class="container">
    <h1>🧹 Limpeza de Arquivos Órfãos</h1>

    <div class="aviso">
        <strong>⚠️ ATENÇÃO:</strong> Este script identifica e pode remover arquivos órfãos (sem referência no banco de dados).
        <br>Sempre execute primeiro em MODO TESTE!
    </div>

    <form method="post">
        <fieldset>
            <legend>Configurações de Limpeza</legend>

            <p>
                <label>
                    <input type="radio" name="modo" value="teste" <?php echo $MODO_TESTE ? 'checked' : ''; ?>>
                    <strong>MODO TESTE</strong> (apenas simula, não faz alterações)
                </label>
            </p>
            <p>
                <label>
                    <input type="radio" name="modo" value="real" <?php echo !$MODO_TESTE ? 'checked' : ''; ?>>
                    <strong>MODO REAL</strong> (executa ações de verdade!)
                </label>
            </p>

            <hr>

            <p>
                <label>
                    <input type="radio" name="acao" value="mover" <?php echo $ACAO == 'mover' ? 'checked' : ''; ?>>
                    <strong>MOVER</strong> para pasta de backup
                </label>
            </p>
            <p>
                <label>
                    <input type="radio" name="acao" value="deletar" <?php echo $ACAO == 'deletar' ? 'checked' : ''; ?>>
                    <strong>DELETAR</strong> permanentemente
                </label>
            </p>

            <button type="submit" name="executar" class="<?php echo $MODO_TESTE ? 'btn-info' : 'btn-danger'; ?>">
                <?php echo $MODO_TESTE ? '🔍 Executar Análise' : '⚠️ Executar Limpeza'; ?>
            </button>
        </fieldset>
    </form>

    <?php
    if (isset($_POST['executar'])) {

        echo "<h2>Resultados da " . ($MODO_TESTE ? "Análise" : "Limpeza") . "</h2>";

        if (!$MODO_TESTE && $ACAO == 'mover') {
            // Criar pasta de backup
            if (!file_exists($PASTA_BACKUP)) {
                mkdir($PASTA_BACKUP, 0777, true);
                mkdir($PASTA_BACKUP . 'atividades/', 0777, true);
                mkdir($PASTA_BACKUP . 'correcao/', 0777, true);
                mkdir($PASTA_BACKUP . 'arquivos/', 0777, true);
            }
            echo "<div class='info'>📁 Pasta de backup criada: $PASTA_BACKUP</div>";
        }

        $total_processados = 0;
        $total_orfaos = 0;
        $espaco_liberado = 0;
        $resultados = [];

        // PROCESSAR CADA PASTA
        $pastas = [
            'atividades' => ['tabela' => 'atividade', 'campo' => 'arquivo'],
            'correcao' => ['tabela' => 'atividade', 'campo' => 'arquivo_correcao'],
            'arquivos' => ['tabela' => 'conteudo', 'campo' => 'arquivo']
        ];

        foreach ($pastas as $nome_pasta => $config) {
            $pasta = $nome_pasta . '/';

            if (is_dir($pasta)) {
                $arquivos = scandir($pasta);
                $resultados[$nome_pasta] = [];

                foreach ($arquivos as $arquivo) {
                    if ($arquivo != '.' && $arquivo != '..') {
                        $total_processados++;
                        $caminho_completo = $pasta . $arquivo;
                        $tamanho = filesize($caminho_completo);

                        // Verificar se existe no banco
                        $sql = "SELECT COUNT(*) as total FROM {$config['tabela']} WHERE {$config['campo']} LIKE '%$arquivo%'";
                        $result = mysql_query($sql);
                        $row = mysql_fetch_assoc($result);

                        $is_orfao = ($row['total'] == 0);

                        $resultados[$nome_pasta][] = [
                            'arquivo' => $arquivo,
                            'tamanho' => $tamanho,
                            'orfao' => $is_orfao,
                            'caminho' => $caminho_completo
                        ];

                        if ($is_orfao) {
                            $total_orfaos++;
                            $espaco_liberado += $tamanho;

                            // Executar ação se não for teste
                            if (!$MODO_TESTE) {
                                if ($ACAO == 'mover') {
                                    $destino = $PASTA_BACKUP . $nome_pasta . '/' . $arquivo;
                                    if (rename($caminho_completo, $destino)) {
                                        $resultados[$nome_pasta][count($resultados[$nome_pasta])-1]['acao'] = 'movido';
                                    } else {
                                        $resultados[$nome_pasta][count($resultados[$nome_pasta])-1]['acao'] = 'erro ao mover';
                                    }
                                } elseif ($ACAO == 'deletar') {
                                    if (unlink($caminho_completo)) {
                                        $resultados[$nome_pasta][count($resultados[$nome_pasta])-1]['acao'] = 'deletado';
                                    } else {
                                        $resultados[$nome_pasta][count($resultados[$nome_pasta])-1]['acao'] = 'erro ao deletar';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // EXIBIR RESULTADOS
        foreach ($resultados as $pasta => $arquivos) {
            if (count($arquivos) > 0) {
                echo "<h3>📁 /$pasta/</h3>";
                echo "<table>";
                echo "<tr><th>Arquivo</th><th>Tamanho</th><th>Status</th>";
                if (!$MODO_TESTE) echo "<th>Ação</th>";
                echo "</tr>";

                foreach ($arquivos as $arquivo) {
                    $classe = $arquivo['orfao'] ? 'orfao' : 'valido';
                    echo "<tr class='$classe'>";
                    echo "<td>{$arquivo['arquivo']}</td>";
                    echo "<td>" . number_format($arquivo['tamanho'] / 1024, 2) . " KB</td>";
                    echo "<td>" . ($arquivo['orfao'] ? '❌ Órfão' : '✅ Válido') . "</td>";
                    if (!$MODO_TESTE && $arquivo['orfao']) {
                        echo "<td>" . ($arquivo['acao'] ?? '') . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
        }

        // RESUMO
        echo "<div class='info' style='margin-top: 30px;'>";
        echo "<h3>📊 Resumo</h3>";
        echo "Total de arquivos analisados: <strong>$total_processados</strong><br>";
        echo "Arquivos órfãos encontrados: <strong>$total_orfaos</strong><br>";
        echo "Espaço " . ($MODO_TESTE ? "que seria liberado" : "liberado") . ": <strong>" . number_format($espaco_liberado / 1024 / 1024, 2) . " MB</strong><br>";

        if (!$MODO_TESTE) {
            if ($ACAO == 'mover') {
                echo "<br>✅ Arquivos movidos para: <strong>$PASTA_BACKUP</strong>";
            } else {
                echo "<br>✅ Arquivos deletados permanentemente.";
            }
        } else {
            echo "<br>ℹ️ <em>Nenhuma alteração foi feita (Modo Teste ativo)</em>";
        }
        echo "</div>";

        // Criar log
        $log_content = "LOG DE LIMPEZA - " . date('d/m/Y H:i:s') . "\n";
        $log_content .= "Modo: " . ($MODO_TESTE ? "TESTE" : "REAL") . "\n";
        $log_content .= "Ação: $ACAO\n";
        $log_content .= "Total órfãos: $total_orfaos\n";
        $log_content .= "Espaço liberado: " . number_format($espaco_liberado / 1024 / 1024, 2) . " MB\n\n";

        foreach ($resultados as $pasta => $arquivos) {
            foreach ($arquivos as $arquivo) {
                if ($arquivo['orfao']) {
                    $log_content .= $arquivo['caminho'] . " - " . ($arquivo['acao'] ?? 'identificado') . "\n";
                }
            }
        }

        $log_file = 'log_limpeza_' . date('Ymd_His') . '.txt';
        file_put_contents($log_file, $log_content);
        echo "<p><a href='$log_file' download>📥 Baixar log da operação</a></p>";
    }
    ?>
</div>

</body>
</html>

<?php
mysql_close($conexao);
?>