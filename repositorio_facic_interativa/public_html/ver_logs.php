<?php
/**
 * Visualizador de Logs JavaScript
 * Mostra os logs de forma formatada e fácil de ler
 */

$logFile = 'logs_javascript_' . date('Y-m-d') . '.txt';
$logPath = __DIR__ . '/' . $logFile;

// Verificar se existe log de hoje, senão procurar últimos 7 dias
$arquivosLog = [];
for ($i = 0; $i < 7; $i++) {
    $data = date('Y-m-d', strtotime("-$i days"));
    $arquivo = 'logs_javascript_' . $data . '.txt';
    $caminho = __DIR__ . '/' . $arquivo;
    if (file_exists($caminho)) {
        $arquivosLog[] = [
            'nome' => $arquivo,
            'data' => $data,
            'tamanho' => filesize($caminho),
            'modificado' => filemtime($caminho)
        ];
    }
}

$logSelecionado = isset($_GET['arquivo']) ? $_GET['arquivo'] : $logFile;
$logPathSelecionado = __DIR__ . '/' . $logSelecionado;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visualizador de Logs - Cadastro de Prova</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }

        .files-list {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .files-list h2 {
            color: #34495e;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
            transition: background 0.2s;
        }

        .file-item:hover {
            background: #f8f9fa;
        }

        .file-item.active {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
        }

        .file-info {
            flex: 1;
        }

        .file-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .file-meta {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 4px;
        }

        .file-action {
            margin-left: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background: #229954;
        }

        .log-viewer {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .log-content {
            background: #282c34;
            color: #abb2bf;
            padding: 20px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            line-height: 1.6;
            max-height: 800px;
            overflow-y: auto;
        }

        .log-entry {
            margin-bottom: 20px;
            border-left: 3px solid #61afef;
            padding-left: 15px;
        }

        .log-timestamp {
            color: #e5c07b;
            font-weight: bold;
        }

        .log-tipo {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 10px;
        }

        .log-tipo.info {
            background: #61afef;
            color: #282c34;
        }

        .log-tipo.sucesso {
            background: #98c379;
            color: #282c34;
        }

        .log-tipo.erro {
            background: #e06c75;
            color: white;
        }

        .log-tipo.aviso {
            background: #e5c07b;
            color: #282c34;
        }

        .log-mensagem {
            color: #d19a66;
            margin: 8px 0;
        }

        .log-dados {
            color: #98c379;
            white-space: pre-wrap;
            margin-top: 8px;
            font-size: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .refresh-btn {
            background: #27ae60;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
        }

        .refresh-btn:hover {
            background: #229954;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            header, .files-list, .log-viewer {
                padding: 15px;
            }

            .file-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .file-action {
                margin-left: 0;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🔍 Visualizador de Logs</h1>
            <p class="subtitle">Sistema de Cadastro de Prova - Logs Automáticos do JavaScript</p>
        </header>

        <?php if (!empty($arquivosLog)): ?>
        <div class="files-list">
            <h2>📁 Arquivos de Log Disponíveis</h2>
            <?php foreach ($arquivosLog as $arquivo): ?>
            <div class="file-item <?php echo $arquivo['nome'] === $logSelecionado ? 'active' : ''; ?>">
                <div class="file-info">
                    <div class="file-name"><?php echo htmlspecialchars($arquivo['nome']); ?></div>
                    <div class="file-meta">
                        📅 <?php echo date('d/m/Y', strtotime($arquivo['data'])); ?> |
                        🕒 Modificado: <?php echo date('H:i:s', $arquivo['modificado']); ?> |
                        📊 Tamanho: <?php echo number_format($arquivo['tamanho'] / 1024, 2); ?> KB
                    </div>
                </div>
                <div class="file-action">
                    <a href="?arquivo=<?php echo urlencode($arquivo['nome']); ?>" class="btn btn-primary">Ver Log</a>
                    <a href="<?php echo htmlspecialchars($arquivo['nome']); ?>" class="btn btn-success" download>Download</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="log-viewer">
            <div class="toolbar">
                <h2>📝 Conteúdo do Log: <?php echo htmlspecialchars(basename($logSelecionado)); ?></h2>
                <a href="?arquivo=<?php echo urlencode($logSelecionado); ?>" class="refresh-btn">🔄 Atualizar</a>
            </div>

            <?php if (file_exists($logPathSelecionado)): ?>
                <div class="log-content">
                    <?php
                    $conteudo = file_get_contents($logPathSelecionado);
                    if (empty(trim($conteudo))) {
                        echo "<div class='empty-state'><div class='empty-state-icon'>📭</div><p>Log vazio - nenhum evento registrado ainda</p></div>";
                    } else {
                        // Processar e colorir o log
                        $conteudo = htmlspecialchars($conteudo);

                        // Destacar timestamps
                        $conteudo = preg_replace('/\[(\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2})\]/', '<span class="log-timestamp">[$1]</span>', $conteudo);

                        // Destacar tipos de log
                        $conteudo = preg_replace('/TIPO: (INFO|SUCESSO|ERRO|AVISO)/i', 'TIPO: <span class="log-tipo $1">$1</span>', $conteudo);

                        // Destacar mensagens
                        $conteudo = preg_replace('/MENSAGEM: (.+)/i', '<span style="color: #d19a66;">MENSAGEM: $1</span>', $conteudo);

                        echo nl2br($conteudo);
                    }
                    ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h3>Nenhum log encontrado</h3>
                    <p style="margin-top: 10px;">
                        O arquivo <code><?php echo htmlspecialchars($logSelecionado); ?></code> não existe ainda.<br>
                        Use o formulário de cadastro de prova para gerar logs.
                    </p>
                    <a href="cadastroProva2_FINAL.php" class="btn btn-primary" style="margin-top: 20px;">
                        Ir para Cadastro de Prova
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 30px; color: #7f8c8d; font-size: 14px;">
            <p>💡 <strong>Dica:</strong> Os logs são atualizados em tempo real. Clique em "Atualizar" para ver as últimas entradas.</p>
        </div>
    </div>
</body>
</html>
