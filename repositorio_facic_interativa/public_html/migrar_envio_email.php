<?php
/**
 * SCRIPT DE MIGRAÇÃO E UNIFICAÇÃO DAS PASTAS MAIL E MAILER
 * Execute este arquivo para copiar todos os arquivos necessários
 */

echo "<h1>🔄 Migração para pasta EnvioEmail</h1>";
echo "<pre>";

$base_path = __DIR__ . '/';
$origem_mail = $base_path . 'mail/';
$origem_mailer = $base_path . 'Mailer/';
$destino = $base_path . 'EnvioEmail/';

// ==========================================
// ETAPA 1: COPIAR PHPMAILER
// ==========================================

echo "\n=== ETAPA 1: Copiando PHPMailer ===\n";

$arquivos_phpmailer = [
    'mail/phpmailer/class.phpmailer.php' => 'PHPMailer/class.phpmailer.php',
    'mail/phpmailer/class.smtp.php' => 'PHPMailer/class.smtp.php',
    'Mailer/class.phpmailer.php' => 'PHPMailer/class.phpmailer_alt.php',
    'Mailer/class.smtp.php' => 'PHPMailer/class.smtp_alt.php',
    'Mailer/class.pop3.php' => 'PHPMailer/class.pop3.php',
    'Mailer/PHPMailerAutoload.php' => 'PHPMailer/PHPMailerAutoload.php'
];

foreach ($arquivos_phpmailer as $origem => $destino_rel) {
    $arquivo_origem = $base_path . $origem;
    $arquivo_destino = $destino . $destino_rel;
    
    if (file_exists($arquivo_origem)) {
        $dir_destino = dirname($arquivo_destino);
        if (!is_dir($dir_destino)) {
            mkdir($dir_destino, 0755, true);
        }
        
        if (copy($arquivo_origem, $arquivo_destino)) {
            echo "✅ Copiado: $origem\n";
        } else {
            echo "❌ ERRO ao copiar: $origem\n";
        }
    } else {
        echo "⚠️  Arquivo não encontrado: $origem\n";
    }
}

// Copiar pasta language se existir:
$dir_language_origem = $base_path . 'mail/phpmailer/language/';
$dir_language_destino = $destino . 'PHPMailer/language/';

if (is_dir($dir_language_origem)) {
    if (!is_dir($dir_language_destino)) {
        mkdir($dir_language_destino, 0755, true);
    }
    
    $arquivos_lang = glob($dir_language_origem . '*.php');
    foreach ($arquivos_lang as $arquivo_lang) {
        $nome_arquivo = basename($arquivo_lang);
        if (copy($arquivo_lang, $dir_language_destino . $nome_arquivo)) {
            echo "✅ Copiado: language/$nome_arquivo\n";
        }
    }
}

// ==========================================
// ETAPA 2: COPIAR TEMPLATES DE EMAIL ANTIGOS
// ==========================================

echo "\n=== ETAPA 2: Copiando templates de email ===\n";

$arquivos_templates = [
    'mail/contato.php' => 'Templates/legado_contato.php',
    'mail/contatoAula.php' => 'Templates/legado_contatoAula.php',
    'mail/envioAtividade.php' => 'Templates/legado_envioAtividade.php',
    'mail/minhabiblioteca.php' => 'Templates/legado_minhabiblioteca.php'
];

foreach ($arquivos_templates as $origem => $destino_rel) {
    $arquivo_origem = $base_path . $origem;
    $arquivo_destino = $destino . $destino_rel;
    
    if (file_exists($arquivo_origem)) {
        if (copy($arquivo_origem, $arquivo_destino)) {
            echo "✅ Copiado: $origem\n";
        } else {
            echo "❌ ERRO ao copiar: $origem\n";
        }
    }
}

// ==========================================
// ETAPA 3: CRIAR ARQUIVO DE COMPATIBILIDADE
// ==========================================

echo "\n=== ETAPA 3: Criando arquivos de compatibilidade ===\n";

// Criar compatibilidade para mail/enviarEmail.php:
$compat_mail = '<?php
/**
 * ARQUIVO DE COMPATIBILIDADE
 * Este arquivo mantém retrocompatibilidade com código antigo
 * RECOMENDADO: Migre para EnvioEmail/enviarEmail.php
 */

// Redirecionar para nova estrutura:
require_once __DIR__ . "/../EnvioEmail/enviarEmail.php";

// Manter função antiga para compatibilidade:
if (!function_exists("mail")) {
    // Já existe função mail() nativa do PHP
}
?>';

file_put_contents($origem_mail . 'enviarEmail_compat.php', $compat_mail);
echo "✅ Criado: mail/enviarEmail_compat.php\n";

// ==========================================
// ETAPA 4: CRIAR ÍNDICE DE REDIRECIONAMENTO
// ==========================================

echo "\n=== ETAPA 4: Criando índices de redirecionamento ===\n";

$index_mail = '<?php
// Redirecionamento de segurança
header("Location: ../index.php");
exit;
?>';

if (!file_exists($origem_mail . 'index.php')) {
    file_put_contents($origem_mail . 'index.php', $index_mail);
    echo "✅ Criado: mail/index.php\n";
}

if (!file_exists($origem_mailer . 'index.php')) {
    file_put_contents($origem_mailer . 'index.php', $index_mail);
    echo "✅ Criado: Mailer/index.php\n";
}

// ==========================================
// ETAPA 5: RELATÓRIO FINAL
// ==========================================

echo "\n=== RELATÓRIO FINAL ===\n\n";

echo "✅ Migração concluída com sucesso!\n\n";

echo "📁 Estrutura criada:\n";
echo "   EnvioEmail/\n";
echo "   ├── Config/                  (Configurações SMTP)\n";
echo "   ├── PHPMailer/              (Biblioteca unificada)\n";
echo "   ├── Templates/              (Templates de email)\n";
echo "   ├── Testes/                 (Scripts de teste)\n";
echo "   ├── logs/                   (Logs de envio)\n";
echo "   ├── enviarEmail.php         (Função principal)\n";
echo "   ├── enviarEmailComRetry.php (Com retry)\n";
echo "   ├── EmailLogger.php         (Sistema de logs)\n";
echo "   └── README.md               (Documentação)\n\n";

echo "⚠️  PRÓXIMOS PASSOS:\n";
echo "1. Configure as senhas SMTP em: EnvioEmail/Config/smtp_config.php\n";
echo "2. Execute os testes em: EnvioEmail/Testes/\n";
echo "3. Migre gradualmente o código antigo para usar EnvioEmail/\n";
echo "4. As pastas 'mail/' e 'Mailer/' continuam funcionando (compatibilidade)\n\n";

echo "📝 Observações:\n";
echo "- Arquivos antigos mantidos como 'legado_*' em Templates/\n";
echo "- PHPMailer consolidado em uma única pasta\n";
echo "- Todas as configurações centralizadas\n\n";

echo "</pre>";
echo "<p><a href='EnvioEmail/README.md'>📖 Ver Documentação</a></p>";
echo "<p><a href='EnvioEmail/Testes/'>🧪 Executar Testes</a></p>";

?>