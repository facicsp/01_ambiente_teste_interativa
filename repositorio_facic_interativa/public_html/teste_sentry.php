<?php
/**
 * Arquivo de Teste - Integração Sentry FACIC Interativa
 * 
 * Este arquivo testa se o Sentry está configurado corretamente
 * e enviando eventos para o painel de monitoramento.
 * 
 * Como usar:
 * 1. Acesse: http://seu-dominio/teste_sentry.php
 * 2. Aguarde 30 segundos
 * 3. Verifique no painel: https://facic.sentry.io/projects/interativa-facic/
 * 
 * @author Sistema de Monitoramento via MCP
 * @date 2025-10-03
 */

require_once __DIR__ . '/sentry_config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Sentry - FACIC Interativa</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #006699;
            padding-bottom: 10px;
        }
        h2 {
            color: #006699;
            margin-top: 30px;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        .info {
            background: #e7f3ff;
            border-left: 4px solid #006699;
            padding: 15px;
            margin: 15px 0;
        }
        .test-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
        }
        a {
            color: #006699;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        ul {
            line-height: 1.8;
        }
        .button {
            background: #006699;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 5px;
        }
        .button:hover {
            background: #004477;
        }
        .timestamp {
            color: #666;
            font-size: 0.9em;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Teste de Integração Sentry - FACIC Interativa</h1>
        
        <div class="info">
            <strong>ℹ️ Sobre este teste:</strong><br>
            Este arquivo verifica se o Sentry está configurado corretamente e capturando eventos.
            Os eventos de teste enviados aqui devem aparecer no painel do Sentry em alguns segundos.
        </div>

        <hr>

        <h2>1. ✅ Status da Inicialização</h2>
        <div class="test-box">
            <?php
            try {
                \Sentry\captureMessage('🧪 Teste de mensagem Sentry - FACIC Interativa [' . date('Y-m-d H:i:s') . ']', \Sentry\Severity::info());
                echo "<p class='success'>✅ Mensagem de teste enviada com sucesso!</p>";
                echo "<p>Verifique no painel do Sentry em alguns segundos.</p>";
            } catch (\Exception $e) {
                echo "<p class='error'>❌ Erro ao enviar mensagem: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>

        <h2>2. 🚨 Teste de Captura de Exceção</h2>
        <div class="test-box">
            <?php
            try {
                throw new \Exception('🔴 Exceção de teste do sistema FACIC Interativa - Teste de monitoramento');
            } catch (\Exception $e) {
                \Sentry\captureException($e);
                echo "<p class='success'>✅ Exceção de teste capturada com sucesso!</p>";
                echo "<p><strong>Mensagem da exceção:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>

        <h2>3. 📚 Teste de Erro Crítico Acadêmico</h2>
        <div class="test-box">
            <?php
            try {
                throw new \Exception('🎓 Erro crítico acadêmico de teste - Sistema FACIC');
            } catch (\Exception $e) {
                capturarErroCriticoAcademico($e, [
                    'modulo' => 'teste_integracao',
                    'tipo' => 'teste_sistema',
                    'usuario' => 'admin_teste',
                    'instituicao' => 'FACIC',
                    'timestamp_teste' => date('Y-m-d H:i:s')
                ]);
                echo "<p class='success'>✅ Erro crítico acadêmico capturado com contexto completo!</p>";
                echo "<p>Este erro inclui tags e contexto específico do sistema acadêmico FACIC.</p>";
            }
            ?>
        </div>

        <h2>4. ⚙️ Informações de Configuração</h2>
        <div class="test-box">
            <ul>
                <li><strong>Organização Sentry:</strong> facic</li>
                <li><strong>Projeto:</strong> interativa-facic</li>
                <li><strong>Ambiente:</strong> production</li>
                <li><strong>Release:</strong> interativa-facic-v1.0.0</li>
                <li><strong>URL do Projeto:</strong> <a href="https://facic.sentry.io/projects/interativa-facic/" target="_blank">https://facic.sentry.io/projects/interativa-facic/</a></li>
                <li><strong>SDK Version:</strong> sentry/sentry v4.15.2</li>
                <li><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
            </ul>
        </div>

        <h2>5. 📊 Próximos Passos</h2>
        <div class="test-box">
            <ol>
                <li>
                    <strong>Aguarde 30 segundos</strong> para que os eventos sejam processados
                </li>
                <li>
                    <strong>Acesse o painel do Sentry:</strong><br>
                    <a href="https://facic.sentry.io/projects/interativa-facic/" target="_blank" class="button">
                        🔗 Abrir Painel Sentry
                    </a>
                </li>
                <li>
                    <strong>Clique em "Issues"</strong> no menu lateral esquerdo
                </li>
                <li>
                    <strong>Verifique os eventos de teste:</strong>
                    <ul>
                        <li>🧪 Teste de mensagem Sentry - FACIC Interativa</li>
                        <li>🔴 Exceção de teste do sistema FACIC Interativa</li>
                        <li>🎓 Erro crítico acadêmico de teste - Sistema FACIC</li>
                    </ul>
                </li>
                <li>
                    <strong>Se os eventos aparecerem:</strong> ✅ Integração está funcionando perfeitamente!
                </li>
                <li>
                    <strong>Se NÃO aparecerem:</strong> ❌ Verifique o DSN e as configurações no arquivo <code>sentry_config.php</code>
                </li>
            </ol>
        </div>

        <h2>6. 🧰 Ferramentas de Debug</h2>
        <div class="test-box">
            <p><strong>Verificações importantes:</strong></p>
            <ul>
                <li>
                    <?php 
                    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
                        echo "<span class='success'>✅ vendor/autoload.php encontrado</span>";
                    } else {
                        echo "<span class='error'>❌ vendor/autoload.php NÃO encontrado - Execute: composer install</span>";
                    }
                    ?>
                </li>
                <li>
                    <?php 
                    if (file_exists(__DIR__ . '/sentry_config.php')) {
                        echo "<span class='success'>✅ sentry_config.php encontrado</span>";
                    } else {
                        echo "<span class='error'>❌ sentry_config.php NÃO encontrado</span>";
                    }
                    ?>
                </li>
                <li>
                    <?php 
                    if (class_exists('\Sentry\SentrySdk')) {
                        echo "<span class='success'>✅ SDK Sentry carregado corretamente</span>";
                    } else {
                        echo "<span class='error'>❌ SDK Sentry não está carregado</span>";
                    }
                    ?>
                </li>
                <li>
                    <?php 
                    if (function_exists('capturarErroCriticoAcademico')) {
                        echo "<span class='success'>✅ Funções customizadas do sistema FACIC carregadas</span>";
                    } else {
                        echo "<span class='error'>❌ Funções customizadas não encontradas</span>";
                    }
                    ?>
                </li>
            </ul>
        </div>

        <h2>7. 📞 Suporte e Documentação</h2>
        <div class="test-box">
            <ul>
                <li>
                    <strong>Guia de Instalação:</strong> 
                    <a href="GUIA_INSTALACAO_SENTRY_MCP.md" target="_blank">GUIA_INSTALACAO_SENTRY_MCP.md</a>
                </li>
                <li>
                    <strong>Documentação Sentry PHP:</strong> 
                    <a href="https://docs.sentry.io/platforms/php/" target="_blank">https://docs.sentry.io/platforms/php/</a>
                </li>
                <li>
                    <strong>Painel FACIC:</strong> 
                    <a href="https://facic.sentry.io/" target="_blank">https://facic.sentry.io/</a>
                </li>
            </ul>
        </div>

        <div class="timestamp">
            <strong>Data/Hora do teste:</strong> <?php echo date('d/m/Y H:i:s'); ?><br>
            <strong>Servidor:</strong> <?php echo $_SERVER['SERVER_NAME']; ?><br>
            <strong>IP do Servidor:</strong> <?php echo $_SERVER['SERVER_ADDR'] ?? 'N/A'; ?>
        </div>
    </div>
</body>
</html>
