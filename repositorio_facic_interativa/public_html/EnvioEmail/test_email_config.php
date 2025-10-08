<?php
/**
 * TESTE DE CONFIGURAÇÃO DE EMAIL
 * Arquivo: EnvioEmail/test_email_config.php
 * Descrição: Script para testar as novas configurações de email
 */

// Carregar configurações:
require_once __DIR__ . '/Config/smtp_config.php';
require_once __DIR__ . '/Config/email_settings.php';

echo "<h1>Teste de Configuração de Email</h1>";

echo "<h2>Configuração SMTP Atual:</h2>";
echo "<pre>";
print_r($SMTP_ACCOUNT);
echo "</pre>";

echo "<h2>Configuração Padrão:</h2>";
echo "<pre>";
print_r($SMTP_DEFAULT);
echo "</pre>";

// Validar configuração
$conta = obterContaSMTP();
$config_padrao = obterConfigPadrao();

echo "<h2>Validação da Configuração:</h2>";
if (validarContaSMTP($conta)) {
    echo "<p style='color: green;'>✓ Configuração SMTP válida</p>";
    echo "<p>Email: " . htmlspecialchars($conta['email']) . "</p>";
    echo "<p>Host: " . htmlspecialchars($conta['host']) . "</p>";
    echo "<p>Porta: " . htmlspecialchars($conta['port']) . "</p>";
    echo "<p>Segurança: " . htmlspecialchars($conta['secure']) . "</p>";
} else {
    echo "<p style='color: red;'>✗ Configuração SMTP inválida</p>";
}

echo "<h2>Configurações Gerais:</h2>";
echo "<pre>";
print_r($EMAIL_CONFIG);
echo "</pre>";

echo "<h2>Configurações de Validação:</h2>";
echo "<pre>";
print_r($VALIDATION_CONFIG);
echo "</pre>";

echo "<h2>Configurações de Template:</h2>";
echo "<pre>";
print_r($TEMPLATE_CONFIG);
echo "</pre>";

echo "<h2>Configurações Anti-Spam:</h2>";
echo "<pre>";
print_r($ANTISPAM_CONFIG);
echo "</pre>";

echo "<h2>Configurações de Log:</h2>";
echo "<pre>";
print_r($LOG_CONFIG);
echo "</pre>";

echo "<h2>Configurações de Retry:</h2>";
echo "<pre>";
print_r($RETRY_CONFIG);
echo "</pre>";

echo "<p><strong>Nota:</strong> Para testar o envio real de email, use o arquivo <code>enviarEmail.php</code> com a função <code>enviarEmail()</code>.</p>";

?>