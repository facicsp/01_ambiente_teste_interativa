<?php
/**
 * CONFIGURAÇÃO DA CONTA SMTP
 * Arquivo: EnvioEmail/Config/smtp_config.php
 * Descrição: Configuração centralizada da conta de email do sistema
 */

// ======================================
// CONTA SMTP ÚNICA DO SISTEMA
// ======================================

$SMTP_ACCOUNT = [
    'email' => 'tecnologia@facicsp.com.br',
    'password' => 'F@cic123@',
    'name' => 'FACIC Interativa',
    'host' => 'email-ssl.com.br',
    'port' => 993,
    'secure' => 'ssl', // SSL para porta 993
    'timeout' => 30
];

// ======================================
// CONFIGURAÇÃO DE REMETENTE PADRÃO
// ======================================

$SMTP_DEFAULT = [
    'from_email' => 'tecnologia@facicsp.com.br',
    'from_name' => 'FACIC Interativa',
    'reply_to_email' => 'tecnologia@facicsp.com.br',
    'reply_to_name' => 'Suporte FACIC'
];

// ======================================
// FUNÇÕES AUXILIARES
// ======================================

/**
 * Retorna a configuração da conta SMTP
 */
function obterContaSMTP() {
    global $SMTP_ACCOUNT;
    return $SMTP_ACCOUNT;
}

/**
 * Valida se a conta tem todas as configurações necessárias
 */
function validarContaSMTP($conta) {
    $campos_obrigatorios = ['email', 'password', 'host', 'port', 'secure'];
    
    foreach ($campos_obrigatorios as $campo) {
        if (empty($conta[$campo])) {
            return false;
        }
    }
    
    return true;
}

/**
 * Retorna as configurações padrão de remetente
 */
function obterConfigPadrao() {
    global $SMTP_DEFAULT;
    return $SMTP_DEFAULT;
}

?>
