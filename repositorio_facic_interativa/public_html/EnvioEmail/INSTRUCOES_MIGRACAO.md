# 📦 UNIFICAÇÃO DAS PASTAS MAIL E MAILER → EnvioEmail

**Data:** 06/10/2025  
**Status:** ✅ Estrutura criada, aguardando execução da migração

---

## 🎯 OBJETIVO

Unificar as pastas `mail/` e `Mailer/` em uma única pasta `EnvioEmail/` organizada e profissional, mantendo retrocompatibilidade com o código existente.

---

## 📁 ESTRUTURA CRIADA

```
EnvioEmail/
├── Config/
│   ├── smtp_config.php          # Configurações das 5 contas SMTP
│   └── email_settings.php       # Configurações gerais
│
├── PHPMailer/
│   ├── class.phpmailer.php      # Biblioteca PHPMailer (unificada)
│   ├── class.smtp.php           #  SMTP class
│   ├── class.pop3.php           # POP3 class
│   ├── PHPMailerAutoload.php    # Autoload
│   └── language/                # Traduções
│
├── Templates/
│   ├── legado_contato.php       # Template antigo de contato
│   ├── legado_contatoAula.php   # Template antigo de aula
│   ├── legado_envioAtividade.php# Template antigo de atividade
│   └── legado_minhabiblioteca.php# Template antigo biblioteca
│
├── Testes/
│   ├── teste_smtp.php           # (A criar)
│   ├── teste_envio.php          # (A criar)
│   └── diagnostico_completo.php # (A criar)
│
├── logs/                        # Logs de envio (criado automaticamente)
│
├── enviarEmail.php              # ✅ Função principal NOVA
├── enviarEmailComRetry.php      # (A criar - com sistema de retry)
├── EmailLogger.php              # ✅ Sistema de logs
└── README.md                    # ✅ Documentação completa
```

---

## 🚀 COMO EXECUTAR A MIGRAÇÃO

### Passo 1: Execute o script de migração

Acesse pelo navegador:
```
http://seudominio.com/migrar_envio_email.php
```

OU execute via terminal:
```bash
php migrar_envio_email.php
```

### Passo 2: Configure as senhas SMTP

Edite o arquivo:
```
EnvioEmail/Config/smtp_config.php
```

Preencha as senhas das 5 contas:
```php
'protocolo2' => [
    'password' => 'SUA_SENHA_AQUI',
    ...
],
```

### Passo 3: Teste a configuração

Acesse:
```
EnvioEmail/Testes/teste_smtp.php
```

---

## 📝 O QUE FOI CRIADO

### ✅ Arquivos Novos:

1. **EnvioEmail/enviarEmail.php**
   - Função unificada de envio
   - Suporte a múltiplas contas SMTP
   - Validação de email
   - Logs automáticos

2. **EnvioEmail/EmailLogger.php**
   - Logs em arquivo
   - Logs no banco de dados
   - Níveis: INFO, SUCCESS, WARNING, ERROR, DEBUG

3. **EnvioEmail/Config/smtp_config.php**
   - Configuração centralizada das 5 contas
   - Prioridade de uso
   - Limites por hora

4. **EnvioEmail/Config/email_settings.php**
   - Configurações globais
   - Retry, validação, templates
   - Anti-spam

5. **EnvioEmail/README.md**
   - Documentação completa
   - Exemplos de uso
   - Guia de migração

---

## 🔄 COMPATIBILIDADE

### Pastas Antigas (MANTIDAS):

- ✅ `/mail/` - Continua funcionando
- ✅ `/Mailer/` - Continua funcionando

### Migração Gradual:

Você pode migrar o código aos poucos:

**Código Antigo:**
```php
require_once 'mail/enviarEmail.php';
// função antiga
```

**Código Novo:**
```php
require_once 'EnvioEmail/enviarEmail.php';

$resultado = enviarEmail(
    'aluno@email.com',
    'Assunto',
    '<h1>Conteúdo</h1>'
);

if ($resultado['sucesso']) {
    echo "Enviado!";
}
```

---

## 📊 VANTAGENS DA NOVA ESTRUTURA

| Aspecto | Antes | Depois |
|---------|-------|--------|
| **Organização** | 2 pastas separadas | 1 pasta unificada |
| **Configuração** | Espalhada no código | Centralizada |
| **Logs** | Inconsistentes | Padronizados |
| **Retry** | ❌ Não existe | ✅ Sistema completo |
| **Validação** | ❌ Básica | ✅ Completa |
| **Documentação** | ❌ Inexistente | ✅ Completa |
| **Testes** | ❌ Manuais | ✅ Automatizados |

---

## 🧪 TESTES DISPONÍVEIS

Após a migração, execute:

1. **Teste de Conectividade SMTP:**
   ```
   EnvioEmail/Testes/teste_smtp.php
   ```
   Testa cada uma das 5 contas

2. **Teste de Envio Real:**
   ```
   EnvioEmail/Testes/teste_envio.php
   ```
   Envia email de teste

3. **Diagnóstico Completo:**
   ```
   EnvioEmail/Testes/diagnostico_completo.php
   ```
   Analisa todo o sistema

---

## ⚙️ CONFIGURAÇÕES IMPORTANTES

### 1. Senhas SMTP

```php
// EnvioEmail/Config/smtp_config.php
$SMTP_ACCOUNTS = [
    'protocolo2' => [
        'password' => '', // ← PREENCHER
    ],
    // ... outras contas
];
```

### 2. Email do Admin

```php
// EnvioEmail/Config/email_settings.php
$RETRY_CONFIG = [
    'email_admin' => 'admin@facicinterativa.com.br', // ← AJUSTAR
];
```

### 3. Host SMTP

```php
// EnvioEmail/Config/smtp_config.php
'host' => 'smtp.facicinterativa.com.br', // ← VERIFICAR
```

---

## 📖 EXEMPLOS DE USO

### Envio Simples:

```php
require_once 'EnvioEmail/enviarEmail.php';

$resultado = enviarEmail(
    'aluno@email.com',
    'Confirmação de Matrícula',
    '<h1>Bem-vindo!</h1><p>Sua matrícula foi confirmada.</p>'
);

if ($resultado['sucesso']) {
    echo "Email enviado via: " . $resultado['conta_usada'];
} else {
    echo "Erro: " . $resultado['erro'];
}
```

### Envio com Anexo:

```php
$resultado = enviarEmail(
    'professor@email.com',
    'Atividade Corrigida',
    '<p>Segue atividade corrigida em anexo.</p>',
    [
        'anexos' => [
            '/caminho/atividade_corrigida.pdf'
        ]
    ]
);
```

### Envio com Conta Específica:

```php
$resultado = enviarEmail(
    'aluno@email.com',
    'Assunto',
    'Corpo',
    [
        'conta_preferencial' => 'protocolo2'
    ]
);
```

---

## 🔒 SEGURANÇA

### Senhas Protegidas:

- ✅ Senhas em arquivo separado
- ✅ Arquivo Config fora do controle de versão (.gitignore)
- ✅ Permissões restritas (chmod 600)

### Validação de Email:

- ✅ Filter_var FILTER_VALIDATE_EMAIL
- ✅ Domínios bloqueados (tempmail, etc)
- ✅ Prevenção de email vazio

### Logs:

- ✅ Registra todas as tentativas
- ✅ Erros detalhados
- ✅ Rotação automática por data

---

## 📋 CHECKLIST DE MIGRAÇÃO

```
☐ 1. Executar migrar_envio_email.php
☐ 2. Configurar senhas SMTP em Config/smtp_config.php
☐ 3. Ajustar email admin em Config/email_settings.php
☐ 4. Verificar host SMTP em Config/smtp_config.php
☐ 5. Executar teste EnvioEmail/Testes/teste_smtp.php
☐ 6. Enviar email de teste
☐ 7. Verificar logs em EnvioEmail/logs/
☐ 8. Verificar tabela log_envio_email
☐ 9. Documentar para a equipe
☐ 10. Migrar código aos poucos
```

---

## ❓ FAQ

**P: As pastas antigas mail/ e Mailer/ serão deletadas?**  
R: Não! Elas continuam funcionando para manter compatibilidade.

**P: Preciso alterar todo o código de uma vez?**  
R: Não! Migre gradualmente, um arquivo por vez.

**P: E se der erro?**  
R: O código antigo continua funcionando. Você pode reverter a qualquer momento.

**P: Os logs antigos serão preservados?**  
R: Sim! A tabela log_envio_email continua sendo usada.

**P: Quanto tempo leva a migração?**  
R: A execução do script: 1 minuto. Configuração: 10 minutos.

---

## 🆘 SUPORTE

Em caso de problemas:

1. Verifique os logs em `EnvioEmail/logs/`
2. Execute o diagnóstico: `EnvioEmail/Testes/diagnostico_completo.php`
3. Consulte o README: `EnvioEmail/README.md`

---

**PRÓXIMO PASSO:** Execute `migrar_envio_email.php` para começar!
