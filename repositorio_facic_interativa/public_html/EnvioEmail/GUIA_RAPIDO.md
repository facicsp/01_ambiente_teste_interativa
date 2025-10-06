# 🚀 GUIA RÁPIDO - EnvioEmail

## 📍 Acesso Rápido

**Interface Web:** `http://seudominio.com/EnvioEmail/`  
**Migração:** `http://seudominio.com/migrar_envio_email.php`

---

## ⚡ Início Rápido (5 minutos)

### 1. Execute a Migração
```
http://seudominio.com/migrar_envio_email.php
```

### 2. Configure Senhas
Edite: `EnvioEmail/Config/smtp_config.php`
```php
'protocolo2' => ['password' => 'SUA_SENHA'],
'protocolo3' => ['password' => 'SUA_SENHA'],
'protocolo4' => ['password' => 'SUA_SENHA'],
'protocolo5' => ['password' => 'SUA_SENHA'],
```

### 3. Use no Código
```php
require_once 'EnvioEmail/enviarEmail.php';

$resultado = enviarEmail(
    'destinatario@email.com',
    'Assunto',
    '<h1>Conteúdo HTML</h1>'
);

if ($resultado['sucesso']) {
    echo "✅ Enviado via: " . $resultado['conta_usada'];
}
```

---

## 📂 Estrutura de Pastas

```
EnvioEmail/
├── Config/           # Configurações SMTP
├── PHPMailer/        # Biblioteca
├── Templates/        # Modelos de email
├── Testes/           # Scripts de teste
├── logs/             # Logs automáticos
├── enviarEmail.php   # Função principal
└── index.php         # Interface visual
```

---

## 🔧 Configurações Importantes

### Senhas SMTP
📁 `Config/smtp_config.php` → Linha 15-50

### Email Admin
📁 `Config/email_settings.php` → Linha 69

### Host SMTP
📁 `Config/smtp_config.php` → Linha 18

---

## 💡 Exemplos Comuns

### Envio Simples
```php
enviarEmail('aluno@email.com', 'Assunto', 'Mensagem');
```

### Com Anexo
```php
enviarEmail('prof@email.com', 'Assunto', 'Mensagem', [
    'anexos' => ['/caminho/arquivo.pdf']
]);
```

### Conta Específica
```php
enviarEmail('user@email.com', 'Assunto', 'Mensagem', [
    'conta_preferencial' => 'protocolo2'
]);
```

---

## 🔍 Verificar Logs

### Arquivo
📁 `EnvioEmail/logs/emails_YYYY-MM-DD.log`

### Banco de Dados
```sql
SELECT * FROM log_envio_email 
ORDER BY data_log DESC 
LIMIT 20;
```

---

## ⚠️ Troubleshooting

| Erro | Solução |
|------|---------|
| SMTP connect() failed | Verificar senha em Config/ |
| Email vazio | Validar destinatário antes |
| Timeout | Aumentar timeout em Config/ |
| Todas contas falham | Testar manualmente cada uma |

---

## 📞 Links Úteis

- **Interface:** `EnvioEmail/index.php`
- **Documentação:** `EnvioEmail/README.md`
- **Guia Migração:** `EnvioEmail/INSTRUCOES_MIGRACAO.md`
- **Configurar SMTP:** `EnvioEmail/Config/smtp_config.php`
- **Ver Logs:** `EnvioEmail/logs/`

---

## ✅ Checklist Rápido

```
☐ Executou migração?
☐ Configurou 5 senhas SMTP?
☐ Verificou host SMTP?
☐ Testou envio?
☐ Verificou logs?
```

---

**Última atualização:** 06/10/2025  
**Versão:** 2.0
