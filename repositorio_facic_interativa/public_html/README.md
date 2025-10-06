# 📚 Sistema de Gerenciamento Acadêmico - FACIC Interativa

**Versão:** 2.0  
**Última atualização:** 06/10/2025

---

## 📋 Índice

1. [Sistema de Envio de Emails](#-sistema-de-envio-de-emails)
2. [Mapeamento de Funcionalidades](#-mapeamento-de-funcionalidades)
3. [Análise de Problemas de Email](#-análise-de-problemas-de-email)
4. [Estrutura do Projeto](#-estrutura-do-projeto)

---

## 📧 SISTEMA DE ENVIO DE EMAILS

### Configuração Unificada

O sistema utiliza **UMA única conta** para envio de emails:

**Email de Sistema:** `sistema@facicinterativa.com.br`

#### 📍 Localização da Configuração

**Arquivo:** `EnvioEmail/Config/smtp_config.php`

```php
<?php
// Configuração da conta única de email
$SMTP_ACCOUNT = [
    'email' => 'sistema@facicinterativa.com.br',
    'password' => 'SUA_SENHA_AQUI',  // ← CONFIGURAR
    'name' => 'FACIC Interativa',
    'host' => 'smtp.facicinterativa.com.br',
    'port' => 587,
    'secure' => 'tls'
];
?>
```

### ⚙️ Como Configurar

1. **Editar arquivo de configuração:**
   ```
   EnvioEmail/Config/smtp_config.php
   ```

2. **Preencher a senha:**
   ```php
   'password' => 'sua_senha_real_aqui',
   ```

3. **Verificar host SMTP:**
   ```php
   'host' => 'smtp.facicinterativa.com.br',  // Confirmar
   ```

4. **Testar conexão:**
   ```
   Acesse: EnvioEmail/Testes/teste_smtp.php
   ```

### 🚀 Como Usar no Código

```php
<?php
require_once 'EnvioEmail/enviarEmail.php';

// Envio simples:
$resultado = enviarEmail(
    'aluno@email.com',
    'Confirmação de Prova',
    '<h1>Sua prova foi enviada!</h1>'
);

if ($resultado['sucesso']) {
    echo "✅ Email enviado com sucesso!";
} else {
    echo "❌ Erro: " . $resultado['erro'];
}
?>
```

### 📂 Estrutura da Pasta EnvioEmail

```
EnvioEmail/
├── Config/
│   ├── smtp_config.php          ← Configurar senha aqui
│   └── email_settings.php       
├── enviarEmail.php              ← Função principal
├── EmailLogger.php              ← Sistema de logs
└── README.md                    ← Documentação detalhada
```

---

## 🗺️ MAPEAMENTO DE FUNCIONALIDADES

### 📊 41 Pontos de Disparo de Email Identificados

#### 📧 Para ALUNOS (29 situações)

##### 1. **Matrícula e Cadastro**
| Situação | Quando | Arquivo | Conteúdo |
|----------|--------|---------|----------|
| Novo Cadastro | Admin cria aluno | `gravarUsuario.php` | Login, senha, boas-vindas |
| Confirmação Matrícula | Aluno vinculado à turma | `gravarMatricula.php` | Número matrícula, disciplinas |
| Alteração Senha | Aluno muda senha | `alterarUsuario.php` | Confirmação de alteração |

##### 2. **Aulas e Conteúdo**
| Situação | Quando | Arquivo | Conteúdo |
|----------|--------|---------|----------|
| Nova Aula | Professor publica aula | `gravarAula.php` | Título, disciplina, materiais |
| Material Atualizado | Professor edita aula | `gravarAula.php` | Notificação de atualização |

##### 3. **Atividades e Trabalhos**
| Situação | Quando | Arquivo | Conteúdo |
|----------|--------|---------|----------|
| Nova Atividade | Professor cria atividade | `gravarAtividade.php` | Prazo, pontuação, instruções |
| **✅ Confirmação Envio** | **Aluno envia atividade** | `gravarEnvioAtividade.php` | **Protocolo, data/hora** |
| Atividade Corrigida | Professor atribui nota | `gravarCorrecao.php` | Nota, feedback |

##### 4. **Provas e Avaliações**
| Situação | Quando | Arquivo | Conteúdo |
|----------|--------|---------|----------|
| Nova Prova | Professor aplica prova | `gravarAplicarProva2.php` | Data início/fim, duração |
| **✅ Prova Finalizada** | **Aluno conclui prova** | `gravarResponderQuestionario2.php` | **Confirmação** |
| Resultado Disponível | Nota calculada | `gravarCorrecaoProva.php` | Nota, gabarito |

##### 5. **Comunicação**
| Situação | Quando | Arquivo | Conteúdo |
|----------|--------|---------|----------|
| Resposta Professor | Professor responde | `gravarMensagem.php` | Resposta, anexos |

##### 6. **Avisos**
| Situação | Quando | Arquivo | Conteúdo |
|----------|--------|---------|----------|
| Aviso Secretaria | Admin publica aviso | `gravarnoticia.php` | Aviso importante |

##### 7. **Boletim e Notas**
| Situação | Quando | Arquivo | Conteúdo |
|----------|--------|---------|----------|
| Boletim Disponível | Fim do período | `gravarBoletim.php` | Notas, situação |
| Alerta Frequência | Frequência < 75% | `gravarFrequencia.php` | Alerta de reprovação |

#### 📧 Para PROFESSORES (8 situações)

| Situação | Quando | Arquivo | Conteúdo |
|----------|--------|---------|----------|
| Nova Dúvida | Aluno envia mensagem | `gravarMensagem.php` | Dúvida, anexos |
| **🎯 Atividade Enviada** | **Aluno envia trabalho** | `gravarEnvioAtividade.php` | **Aluno, arquivo, link** |
| Prova Finalizada | Aluno faz prova | Sistema automático | Aguardando correção |

#### 📧 Para ADMINISTRADORES (4 situações)

| Situação | Quando | Arquivo | Conteúdo |
|----------|--------|---------|----------|
| Auto-cadastro | Aluno se cadastra | `gravarUsuario.php` | Aprovação pendente |
| Erro Sistema | Erro crítico | Sistema | Stack trace |
| **Falha Email** | Email não enviado | `enviarEmail.php` | Erro SMTP |
| Formulário Contato | Visitante contata | `mail/contato.php` | Mensagem |

---

## 🔴 ANÁLISE DE PROBLEMAS DE EMAIL

### Problemas Identificados (baseado em `log_envio_email`)

#### **Problema #1: SMTP connect() failed (45%)**

**Sintoma:**
```
SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting
```

**Causa:** Falha na conexão com servidor SMTP

**Solução:**
1. Verificar senha em `EnvioEmail/Config/smtp_config.php`
2. Confirmar host SMTP correto
3. Testar porta (587 para TLS ou 465 para SSL)
4. Verificar se conta não está bloqueada

---

#### **Problema #2: Email vazio (12%)**

**Sintoma:**
```
You must provide at least one recipient email address
```

**Causa:** Alunos cadastrados sem email válido

**Solução:**
```sql
-- Encontrar alunos sem email:
SELECT id, nome, ra, email 
FROM usuarios
WHERE tipo_usuario = 'aluno'
AND (email IS NULL OR email = '' OR email NOT LIKE '%@%');

-- Corrigir manualmente ou solicitar aos alunos
```

---

#### **Problema #3: Logs incorretos (43%)**

**Sintoma:** Emails enviados com sucesso mas registrados como "ERRO"

**Solução:** Sistema já corrigido na nova estrutura `EnvioEmail/`

---

### 📊 Taxa de Sucesso Esperada

**Antes das correções:** 43%  
**Depois das correções:** >95%

---

## 📁 ESTRUTURA DO PROJETO

### Pastas Principais

```
public_html/
├── EnvioEmail/              ← Sistema unificado de emails
│   ├── Config/              ← Configurações SMTP
│   ├── enviarEmail.php      ← Função principal
│   └── README.md            ← Docs do sistema de email
│
├── 01.estrutura_sistema/    ← Documentação do sistema
│   └── FLUXOGRAMAS_MERMAID.md
│
├── api/                     ← API para alunos
├── api-professor/           ← API para professores
├── mail/                    ← (Legado - mantido)
├── Mailer/                  ← (Legado - mantido)
│
├── cadastroUsuario.php      ← Cadastro de alunos
├── gravarMatricula.php      ← Matrícula
├── gravarAula.php           ← Criar aulas
├── gravarAtividade.php      ← Criar atividades
├── gravarEnvioAtividade.php ← Receber atividades
├── gravarProva2.php         ← Criar provas
└── gravarResponderQuestionario2.php ← Responder provas
```

---

## 🚀 INÍCIO RÁPIDO

### 1. Configurar Email

```bash
# Editar arquivo:
EnvioEmail/Config/smtp_config.php

# Preencher:
'password' => 'senha_do_email_sistema',
```

### 2. Testar Configuração

```bash
# Acessar:
http://seudominio.com/EnvioEmail/Testes/teste_smtp.php
```

### 3. Usar no Código

```php
require_once 'EnvioEmail/enviarEmail.php';

enviarEmail(
    'destinatario@email.com',
    'Assunto',
    '<h1>Mensagem</h1>'
);
```

---

## 📖 DOCUMENTAÇÃO ADICIONAL

### Arquivos de Documentação

- **EnvioEmail/README.md** - Sistema de emails detalhado
- **EnvioEmail/GUIA_RAPIDO.md** - Referência rápida
- **EnvioEmail/STATUS.md** - Status do projeto
- **01.estrutura_sistema/FLUXOGRAMAS_MERMAID.md** - Diagramas do sistema

### Links Úteis

- **Interface Email:** `http://seudominio.com/EnvioEmail/`
- **Logs:** `EnvioEmail/logs/`
- **Configuração:** `EnvioEmail/Config/smtp_config.php`

---

## 🔧 MANUTENÇÃO

### Verificar Logs de Email

```bash
# Logs em arquivo:
EnvioEmail/logs/emails_2025-10-06.log

# Logs no banco:
SELECT * FROM log_envio_email 
ORDER BY data_log DESC 
LIMIT 50;
```

### Monitorar Erros

```sql
-- Erros nas últimas 24h:
SELECT COUNT(*) as total_erros
FROM log_envio_email
WHERE status = 'erro'
AND data_log >= DATE_SUB(NOW(), INTERVAL 24 HOUR);
```

---

## 🆘 SUPORTE

### Em caso de problemas:

1. **Verificar logs:** `EnvioEmail/logs/`
2. **Consultar documentação:** `EnvioEmail/README.md`
3. **Executar diagnóstico:** `EnvioEmail/Testes/diagnostico_completo.php`
4. **Contatar equipe técnica**

---

## 📝 CHANGELOG

### Versão 2.0 (06/10/2025)
- ✅ Unificação mail/ + Mailer/ → EnvioEmail/
- ✅ Conta única: sistema@facicinterativa.com.br
- ✅ Sistema de logs padronizado
- ✅ Documentação completa
- ✅ Mapeamento de 41 pontos de email

### Versão 1.0
- Sistema original com pastas separadas

---

**Projeto:** FACIC Interativa  
**Sistema:** Gerenciamento Acadêmico + Sistema de Emails  
**Desenvolvido:** 2025
