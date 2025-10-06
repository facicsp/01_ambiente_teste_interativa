# 📧 Sistema Unificado de Envio de Emails - FACIC Interativa

**Data de Criação:** <?php echo date('d/m/Y'); ?>  
**Versão:** 2.0  
**Status:** ✅ Ativo

---

## 📁 Estrutura de Pastas

```
EnvioEmail/
├── Config/               # Configurações SMTP e credenciais
│   ├── smtp_config.php   # Configurações das contas SMTP
│   └── email_settings.php# Configurações gerais de email
│
├── PHPMailer/            # Biblioteca PHPMailer (unificada)
│   ├── class.phpmailer.php
│   ├── class.smtp.php
│   └── language/         # Traduções
│
├── Templates/            # Templates de email
│   ├── confirmacao_prova.php
│   ├── confirmacao_atividade.php
│   ├── mensagem_professor.php
│   └── ...
│
├── Testes/               # Scripts de teste
│   ├── teste_smtp.php
│   ├── teste_envio_prova.php
│   └── diagnostico_completo.php
│
├── enviarEmail.php       # Função principal de envio
├── enviarEmailComRetry.php # Envio com sistema de retry
├── EmailLogger.php       # Classe para logs
└── README.md            # Esta documentação
```

---

## 🚀 Como Usar

### Envio Simples:

```php
<?php
require_once 'EnvioEmail/enviarEmail.php';

$resultado = enviarEmail(
    'aluno@email.com',
    'Assunto do Email',
    '<h1>Conteúdo HTML</h1>'
);

if ($resultado) {
    echo "Email enviado com sucesso!";
} else {
    echo "Falha no envio.";
}
?>
```

### Envio com Retry (Recomendado):

```php
<?php
require_once 'EnvioEmail/enviarEmailComRetry.php';

$resultado = enviarEmailComRetry(
    'aluno@email.com',
    'Assunto do Email',
    '<h1>Conteúdo HTML</h1>',
    'prova',  // tipo: prova, atividade, mensagem
    12345     // ID de referência
);
?>
```

---

## ⚙️ Configuração

### 1. Configure as contas SMTP em `Config/smtp_config.php`
### 2. Ajuste as configurações gerais em `Config/email_settings.php`
### 3. Execute o teste: `Testes/teste_smtp.php`

---

## 🔧 Migração das Pastas Antigas

**Pastas antigas (DESCONTINUADAS):**
- ❌ `/mail/` - Mantida apenas para retrocompatibilidade
- ❌ `/Mailer/` - Mantida apenas para retrocompatibilidade

**Nova pasta (USAR):**
- ✅ `/EnvioEmail/` - Use esta para tudo novo

**Nota:** As pastas antigas continuarão funcionando mas TODO código novo deve usar `/EnvioEmail/`

---

## 📊 Logs

Todos os envios são registrados em:
- Tabela: `log_envio_email`
- Arquivo: `EnvioEmail/logs/emails_YYYY-MM-DD.log`

---

## 🧪 Testes

Execute os testes na pasta `Testes/`:

```bash
# Teste de conectividade SMTP:
php Testes/teste_smtp.php

# Diagnóstico completo:
php Testes/diagnostico_completo.php

# Teste de envio de prova:
php Testes/teste_envio_prova.php
```

---

## 📞 Suporte

Para dúvidas ou problemas, contate a equipe técnica.

**Última atualização:** <?php echo date('d/m/Y H:i:s'); ?>
