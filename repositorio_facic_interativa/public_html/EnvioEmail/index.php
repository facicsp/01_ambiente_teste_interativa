<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnvioEmail - Sistema Unificado</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        .header h1 {
            color: #333;
            font-size: 36px;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 18px;
        }
        .status {
            display: inline-block;
            padding: 8px 16px;
            background: #10b981;
            color: white;
            border-radius: 20px;
            margin-top: 10px;
            font-weight: 600;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .card h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 22px;
        }
        .card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
            font-weight: 600;
            margin-right: 10px;
            margin-top: 5px;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-success {
            background: #10b981;
        }
        .btn-success:hover {
            background: #059669;
        }
        .btn-warning {
            background: #f59e0b;
        }
        .btn-warning:hover {
            background: #d97706;
        }
        .btn-danger {
            background: #ef4444;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .steps {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .step-number {
            background: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .step-content h3 {
            color: #333;
            margin-bottom: 5px;
        }
        .step-content p {
            color: #666;
            margin: 0;
        }
        .footer {
            text-align: center;
            color: white;
            margin-top: 30px;
            padding: 20px;
        }
        .alert {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert strong {
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">📧</div>
            <h1>EnvioEmail - Sistema Unificado</h1>
            <p>Gerenciamento centralizado de envio de emails</p>
            <span class="status">✅ Estrutura Criada</span>
        </div>

        <div class="alert">
            <strong>⚠️ Atenção:</strong> Execute a migração primeiro e depois configure as senhas SMTP antes de usar o sistema.
        </div>

        <div class="grid">
            <div class="card">
                <h2>🚀 Iniciar Migração</h2>
                <p>Execute o script de migração para copiar todos os arquivos das pastas antigas para a nova estrutura unificada.</p>
                <a href="../migrar_envio_email.php" class="btn btn-success">Executar Migração</a>
            </div>

            <div class="card">
                <h2>⚙️ Configurações</h2>
                <p>Configure as senhas SMTP e ajuste as configurações gerais do sistema de envio de emails.</p>
                <a href="Config/smtp_config.php" class="btn">Configurar SMTP</a>
                <a href="Config/email_settings.php" class="btn">Configurações Gerais</a>
            </div>

            <div class="card">
                <h2>🧪 Testes</h2>
                <p>Execute testes para verificar se tudo está funcionando corretamente após a configuração.</p>
                <a href="Testes/" class="btn btn-warning">Ver Testes</a>
            </div>

            <div class="card">
                <h2>📊 Logs</h2>
                <p>Visualize os logs de envio de emails para monitorar o sistema e identificar problemas.</p>
                <a href="logs/" class="btn">Ver Logs</a>
            </div>

            <div class="card">
                <h2>📖 Documentação</h2>
                <p>Consulte a documentação completa do sistema com exemplos de uso e guias.</p>
                <a href="README.md" class="btn">README</a>
                <a href="INSTRUCOES_MIGRACAO.md" class="btn">Guia de Migração</a>
            </div>

            <div class="card">
                <h2>📝 Templates</h2>
                <p>Modelos de email salvos das pastas antigas para referência e reutilização.</p>
                <a href="Templates/" class="btn">Ver Templates</a>
            </div>
        </div>

        <div class="steps">
            <h2 style="color: #333; margin-bottom: 20px;">📋 Passo a Passo para Começar</h2>
            
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Executar Migração</h3>
                    <p>Clique em "Executar Migração" acima para copiar os arquivos das pastas antigas (mail/ e Mailer/)</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Configurar Senhas SMTP</h3>
                    <p>Edite Config/smtp_config.php e preencha as senhas das 5 contas de email (protocolo2 a protocolo5)</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Verificar Host SMTP</h3>
                    <p>Confirme se o host SMTP está correto (smtp.facicinterativa.com.br) no arquivo de configuração</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3>Executar Testes</h3>
                    <p>Execute os testes disponíveis para verificar se as contas SMTP estão funcionando</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">5</div>
                <div class="step-content">
                    <h3>Migrar Código</h3>
                    <p>Comece a migrar gradualmente o código antigo para usar a nova estrutura EnvioEmail/</p>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Sistema criado em: <?php echo date('d/m/Y H:i:s'); ?></p>
            <p style="margin-top: 10px; opacity: 0.8;">
                As pastas antigas (mail/ e Mailer/) continuam funcionando para manter compatibilidade
            </p>
        </div>
    </div>
</body>
</html>