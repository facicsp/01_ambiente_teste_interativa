<?php
/**
 * SCRIPT DE VALIDAÇÃO PÓS-UPLOAD
 * Verifica se os arquivos foram atualizados corretamente
 */

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Validação de Upload - Cadastro Prova</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }
        .check {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        .check.success {
            background: #d4edda;
            border-left: 5px solid #28a745;
        }
        .check.error {
            background: #f8d7da;
            border-left: 5px solid #dc3545;
        }
        .check.warning {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
        }
        .icon {
            font-size: 24px;
            margin-right: 15px;
        }
        .details {
            flex: 1;
        }
        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        pre {
            background: #282c34;
            color: #abb2bf;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 13px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        .button:hover {
            background: #2980b9;
        }
        .timestamp {
            color: #7f8c8d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Validação de Upload - Cadastro de Prova</h1>
        <p class="timestamp">Executado em: <?php echo date('d/m/Y H:i:s'); ?></p>

        <h2>1. Verificação de Arquivos</h2>

        <?php
        $arquivos = [
            'js/criarProva.js' => 'Arquivo JavaScript principal (CRÍTICO)',
            'js/criarProva_LOG.js' => 'Arquivo JavaScript de debug (opcional)',
            'gravarProva2.php' => 'Backend PHP principal',
            'gravarProva2_LOG.php' => 'Backend PHP de debug (opcional)',
            'cadastroProva2.php' => 'Página HTML/PHP',
        ];

        foreach ($arquivos as $arquivo => $descricao) {
            $caminho = __DIR__ . '/' . $arquivo;
            $existe = file_exists($caminho);

            if ($existe) {
                $modificado = filemtime($caminho);
                $idade = time() - $modificado;
                $idadeStr = $idade < 60 ? "$idade segundos" : ($idade < 3600 ? round($idade/60) . " minutos" : round($idade/3600) . " horas");

                // Crítico se foi modificado recentemente
                $classe = $idade < 300 ? 'success' : 'warning'; // 5 minutos
                $icone = $idade < 300 ? '✅' : '⚠️';

                echo "<div class='check $classe'>";
                echo "<div class='icon'>$icone</div>";
                echo "<div class='details'>";
                echo "<strong>$arquivo</strong><br>";
                echo "$descricao<br>";
                echo "<small>Modificado: " . date('d/m/Y H:i:s', $modificado) . " ($idadeStr atrás)</small>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='check error'>";
                echo "<div class='icon'>❌</div>";
                echo "<div class='details'>";
                echo "<strong>$arquivo</strong><br>";
                echo "Arquivo não encontrado!";
                echo "</div>";
                echo "</div>";
            }
        }
        ?>

        <h2>2. Análise do Código JavaScript</h2>

        <?php
        $jsPath = __DIR__ . '/js/criarProva.js';
        if (file_exists($jsPath)) {
            $jsContent = file_get_contents($jsPath);

            // Verificar se tem o header X-Requested-With
            $hasHeader = strpos($jsContent, '"X-Requested-With"') !== false || strpos($jsContent, "'X-Requested-With'") !== false;

            if ($hasHeader) {
                echo "<div class='check success'>";
                echo "<div class='icon'>✅</div>";
                echo "<div class='details'>";
                echo "<strong>Header X-Requested-With encontrado!</strong><br>";
                echo "O header foi adicionado corretamente ao axios.";

                // Extrair linhas relevantes
                $lines = explode("\n", $jsContent);
                $relevantLines = [];
                foreach ($lines as $i => $line) {
                    if (stripos($line, 'X-Requested-With') !== false) {
                        // Pegar 3 linhas antes e 3 depois
                        for ($j = max(0, $i-3); $j <= min(count($lines)-1, $i+3); $j++) {
                            $relevantLines[$j] = $lines[$j];
                        }
                    }
                }

                if (!empty($relevantLines)) {
                    echo "<pre>";
                    foreach ($relevantLines as $num => $line) {
                        echo htmlspecialchars($line) . "\n";
                    }
                    echo "</pre>";
                }

                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='check error'>";
                echo "<div class='icon'>❌</div>";
                echo "<div class='details'>";
                echo "<strong>Header X-Requested-With NÃO encontrado!</strong><br>";
                echo "O arquivo JavaScript NÃO foi atualizado corretamente.<br>";
                echo "<strong>AÇÃO NECESSÁRIA:</strong> Faça upload do arquivo corrigido.";
                echo "</div>";
                echo "</div>";
            }

            // Verificar URL do endpoint
            $usesCorrectEndpoint = strpos($jsContent, 'gravarProva2.php') !== false;

            if ($usesCorrectEndpoint) {
                echo "<div class='check success'>";
                echo "<div class='icon'>✅</div>";
                echo "<div class='details'>";
                echo "<strong>Endpoint correto:</strong> <code>gravarProva2.php</code>";
                echo "</div>";
                echo "</div>";
            }

        } else {
            echo "<div class='check error'>";
            echo "<div class='icon'>❌</div>";
            echo "<div class='details'>";
            echo "<strong>Arquivo js/criarProva.js não encontrado!</strong>";
            echo "</div>";
            echo "</div>";
        }
        ?>

        <h2>3. Análise do Código PHP</h2>

        <?php
        $phpPath = __DIR__ . '/conexao.php';
        if (file_exists($phpPath)) {
            $phpContent = file_get_contents($phpPath);

            // Verificar se tem a verificação do X-Requested-With
            $hasCheck = strpos($phpContent, 'HTTP_X_REQUESTED_WITH') !== false;

            if ($hasCheck) {
                echo "<div class='check success'>";
                echo "<div class='icon'>✅</div>";
                echo "<div class='details'>";
                echo "<strong>Verificação de AJAX encontrada em conexao.php</strong><br>";
                echo "O código verifica o header antes de inserir o script.";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='check warning'>";
                echo "<div class='icon'>⚠️</div>";
                echo "<div class='details'>";
                echo "<strong>Verificação de AJAX não encontrada</strong><br>";
                echo "Isso pode causar problemas com respostas JSON.";
                echo "</div>";
                echo "</div>";
            }
        }
        ?>

        <h2>4. Teste de Integração</h2>

        <div class='check warning'>
            <div class='icon'>🧪</div>
            <div class='details'>
                <strong>Teste Manual Necessário</strong><br>
                Este script verifica apenas os arquivos. Para garantir o funcionamento completo:
                <ol>
                    <li>Abra o <a href="cadastroProva2.php" target="_blank">formulário de cadastro</a></li>
                    <li>Abra o Console do navegador (F12)</li>
                    <li>Preencha e envie uma questão de teste</li>
                    <li>Verifique se aparece "✔ Questão salva com sucesso!"</li>
                    <li>Verifique no console se o response é JSON puro (sem &lt;script&gt;)</li>
                </ol>
            </div>
        </div>

        <h2>5. Checklist Final</h2>

        <div class='check <?php echo $hasHeader ? "success" : "error"; ?>'>
            <div class='icon'><?php echo $hasHeader ? "✅" : "❌"; ?></div>
            <div class='details'>
                <strong>Arquivo JavaScript atualizado:</strong>
                <?php echo $hasHeader ? "SIM" : "NÃO"; ?>
            </div>
        </div>

        <div class='check warning'>
            <div class='icon'>⚠️</div>
            <div class='details'>
                <strong>Cache do navegador limpo:</strong> A fazer manualmente (Ctrl+Shift+Del)
            </div>
        </div>

        <div class='check warning'>
            <div class='icon'>⚠️</div>
            <div class='details'>
                <strong>Teste funcional realizado:</strong> A fazer manualmente
            </div>
        </div>

        <h2>6. Próximos Passos</h2>

        <?php if (!$hasHeader): ?>
            <div class='check error'>
                <div class='icon'>🚨</div>
                <div class='details'>
                    <strong>AÇÃO URGENTE NECESSÁRIA</strong><br>
                    O arquivo JavaScript NÃO contém a correção necessária.<br>
                    <strong>Faça upload de:</strong> <code>js/criarProva.js</code>
                </div>
            </div>
        <?php else: ?>
            <div class='check success'>
                <div class='icon'>🎉</div>
                <div class='details'>
                    <strong>Arquivos validados com sucesso!</strong><br>
                    Agora teste o formulário manualmente para confirmar o funcionamento.
                </div>
            </div>
        <?php endif; ?>

        <a href="cadastroProva2.php" class="button">🧪 Testar Cadastro de Prova</a>
        <a href="?" class="button" style="background: #95a5a6;">🔄 Executar Validação Novamente</a>

        <hr style="margin: 40px 0;">

        <p style="color: #7f8c8d; font-size: 14px;">
            <strong>Nota:</strong> Este script verifica apenas a presença dos arquivos e do código corrigido.
            Um teste manual completo é necessário para garantir o funcionamento end-to-end.
        </p>
    </div>
</body>
</html>
