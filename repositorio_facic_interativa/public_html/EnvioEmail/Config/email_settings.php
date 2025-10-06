<?php
/**
 * CONFIGURAÇÕES GERAIS DE EMAIL
 * Arquivo: EnvioEmail/Config/email_settings.php
 * Descrição: Configurações globais do sistema de emails
 */

// ======================================
// CONFIGURAÇÕES GERAIS
// ======================================

$EMAIL_CONFIG = [
    // Charset padrão:
    'charset' => 'UTF-8',
    
    // Timeout de conexão (segundos):
    'timeout' => 30,
    
    // Modo debug (0=desligado, 1=client, 2=server, 3=connection+server):
    'debug_level' => 0,
    
    // Habilitar HTML:
    'is_html' => true,
    
    // Prioridade (1=Alta, 3=Normal, 5=Baixa):
    'priority' => 3,
    
    // Confirmação de leitura:
    'confirmation_receipt' => false,
    
    // Encoding:
    'encoding' => '8bit', // 8bit, 7bit, binary, base64, quoted-printable
    
    // Keep-alive SMTP:
    'smtp_keep_alive' => false
];

// ======================================
// CONFIGURAÇÕES DE LOG
// ======================================

$LOG_CONFIG = [
    // Habilitar log em arquivo:
    'habilitar_log_arquivo' => true,
    
    // Diretório de logs:
    'dir_logs' => __DIR__ . '/../logs/',
    
    // Habilitar log no banco:
    'habilitar_log_banco' => true,
    
    // Tabela de logs:
    'tabela_log' => 'log_envio_email',
    
    // Registrar sucessos:
    'log_sucessos' => true,
    
    // Registrar erros:
    'log_erros' => true
];

// ======================================
// CONFIGURAÇÕES DE RETRY
// ======================================

$RETRY_CONFIG = [
    // Habilitar sistema de retry:
    'habilitar_retry' => true,
    
    // Tabela de fila:
    'tabela_fila' => 'fila_emails_retry',
    
    // Máximo de tentativas:
    'max_tentativas' => 3,
    
    // Intervalo entre tentativas (minutos):
    'intervalo_retry' => 15,
    
    // Notificar admin após falha definitiva:
    'notificar_admin_falha' => true,
    
    // Email do admin:
    'email_admin' => 'admin@facicinterativa.com.br'
];

// ======================================
// CONFIGURAÇÕES DE VALIDAÇÃO
// ======================================

$VALIDATION_CONFIG = [
    // Validar email antes de enviar:
    'validar_email' => true,
    
    // Bloquear envio se email inválido:
    'bloquear_invalido' => true,
    
    // Domains bloqueados:
    'domains_bloqueados' => [
        'tempmail.com',
        'guerrillamail.com',
        '10minutemail.com'
    ],
    
    // Verificar MX record:
    'verificar_mx' => false
];

// ======================================
// TEMPLATES
// ======================================

$TEMPLATE_CONFIG = [
    // Diretório de templates:
    'dir_templates' => __DIR__ . '/../Templates/',
    
    // Template padrão:
    'template_padrao' => 'base.html',
    
    // Incluir assinatura:
    'incluir_assinatura' => true,
    
    // Assinatura HTML:
    'assinatura_html' => '
        <hr style="border: 1px solid #ddd; margin: 20px 0;">
        <p style="font-size: 12px; color: #666;">
            <strong>FACIC Interativa</strong><br>
            Ambiente Virtual de Aprendizagem<br>
            <a href="http://facicinterativa.com.br">facicinterativa.com.br</a>
        </p>
    '
];

// ======================================
// ANTI-SPAM
// ======================================

$ANTISPAM_CONFIG = [
    // Headers adicionais anti-spam:
    'headers_antispam' => true,
    
    // X-Mailer custom:
    'x_mailer' => 'FACIC System v2.0',
    
    // Precedence:
    'precedence' => 'bulk', // bulk, list, junk
    
    // Auto-Submitted:
    'auto_submitted' => 'auto-generated'
];

// ======================================
// CRIAR DIRETÓRIO DE LOGS SE NÃO EXISTIR
// ======================================

if ($LOG_CONFIG['habilitar_log_arquivo']) {
    if (!is_dir($LOG_CONFIG['dir_logs'])) {
        mkdir($LOG_CONFIG['dir_logs'], 0755, true);
    }
}

?>