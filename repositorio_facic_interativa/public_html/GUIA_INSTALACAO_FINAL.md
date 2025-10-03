# 🚀 GUIA DE INSTALAÇÃO - VERSÃO FINAL COM LOG AUTOMÁTICO

## 📦 O que foi criado

### Arquivos NOVOS (devem ser enviados ao servidor):

1. **js/criarProva_FINAL.js** - JavaScript com log automático (SEM anexo)
2. **cadastroProva2_FINAL.php** - Página HTML nova (SEM campo de anexo)
3. **salvar_log_js.php** - Endpoint que recebe logs do JavaScript
4. **ver_logs.php** - Visualizador de logs bonito

### Arquivos EXISTENTES (NÃO mexer):
- ❌ gravarProva2.php (backend - já funciona)
- ❌ conexao.php (banco de dados - já funciona)
- ❌ cadastroProva2.php (versão antiga - manter como backup)

---

## 📤 PASSO 1: Upload dos Arquivos

### Via FTP:

```
UPLOAD OBRIGATÓRIO (4 arquivos):

✅ js/criarProva_FINAL.js          → /public_html/js/criarProva_FINAL.js
✅ cadastroProva2_FINAL.php        → /public_html/cadastroProva2_FINAL.php
✅ salvar_log_js.php               → /public_html/salvar_log_js.php
✅ ver_logs.php                    → /public_html/ver_logs.php
```

**IMPORTANTE:**
- Verifique se a pasta `js/` existe no servidor
- Mantenha a estrutura de pastas correta
- Após upload, confira timestamps dos arquivos

---

## 🧪 PASSO 2: Testar o Sistema

### 2.1 Acessar a nova página:

```
http://www.facicinterativa.com.br/ambiente_QA/cadastroProva2_FINAL.php
```

### 2.2 Preencher o formulário:

**Teste 1 - Questão Objetiva:**
- Título: "Teste de Log Automático"
- Descrição: "Esta é uma questão de teste"
- Alternativa A: "Opção 1"
- Alternativa B: "Opção 2"
- Marcar: A como correta
- Peso: 1
- Clicar: **SALVAR QUESTÃO**

**Resultado esperado:**
- ✅ Mensagem: "✔ Questão salva com sucesso!"
- ✅ Contador aumenta para "2"
- ✅ Formulário é limpo

### 2.3 Visualizar o log:

```
http://www.facicinterativa.com.br/ambiente_QA/ver_logs.php
```

**O que você verá:**
- 📁 Lista de arquivos de log por data
- 📝 Conteúdo do log formatado
- ✅ Todas as ações registradas automaticamente

**Exemplo de log:**
```
[2025-10-01 23:30:45] LOG DO JAVASCRIPT
================================================================================
TIPO: INFO
MENSAGEM: INICIANDO SALVAMENTO DE QUESTÃO

DADOS:
    [tituloValido] => 1
    [descricaoValida] => 1
    [tipo] => objetiva
    ...
```

---

## 🔍 PASSO 3: Interpretar os Logs

### O que o log mostra:

#### ✅ Se estiver funcionando:
```
TIPO: INFO
MENSAGEM: Enviando requisição AJAX

TIPO: INFO
MENSAGEM: Resposta recebida do servidor
DADOS: {status: 200, data: 18111}

TIPO: SUCESSO
MENSAGEM: QUESTÃO SALVA COM SUCESSO!
```

#### ❌ Se houver erro:
```
TIPO: ERRO
MENSAGEM: ERRO NA REQUISIÇÃO AJAX
DADOS: {
  mensagem: "Network Error"
  response: {
    status: 500,
    data: "Parse error..."
  }
}
```

---

## 🐛 PASSO 4: Diagnosticar Problemas

### Problema: "Ops! Houve algum erro"

**Verificar no log:**
1. Abra `ver_logs.php`
2. Procure por `TIPO: ERRO`
3. Leia a mensagem de erro
4. Veja os dados completos

**Erros comuns:**

#### Erro 1: Arquivo não encontrado
```
ERRO: Failed to load resource: the server responded with a status of 404
```
**Solução:** Verifique se `salvar_log_js.php` foi enviado corretamente

#### Erro 2: Response vazio
```
MENSAGEM: Resposta vazia ou inválida do servidor
DADOS: {data: ""}
```
**Solução:** O PHP está retornando vazio. Veja o próximo passo.

#### Erro 3: Script no JSON
```
DADOS: {data: "<script>..."}
```
**Solução:** O header X-Requested-With não está funcionando. Veja solução alternativa abaixo.

---

## 🔧 PASSO 5: Solução Alternativa (se o problema persistir)

Se mesmo com log você descobrir que o `<script>` ainda está sendo inserido, vamos usar uma abordagem diferente:

### Modificar o gravarProva2.php

**Adicione no INÍCIO do arquivo (linha 2):**

```php
<?php
// Desabilitar output buffering para evitar injeção de scripts
ob_clean();
ob_start();

session_start();
...
```

**Adicione no FINAL do arquivo (antes do último ?>):**

```php
// Limpar qualquer saída anterior e retornar apenas JSON
$output = ob_get_clean();
header('Content-Type: application/json');
echo json_encode($idProva);
?>
```

---

## 📊 PASSO 6: Verificar no Banco de Dados

### Consultas SQL para validar:

```sql
-- Verificar última prova criada
SELECT * FROM prova
ORDER BY idProva DESC
LIMIT 1;

-- Verificar questões da prova
SELECT * FROM questao2
WHERE idProva = [ID_DA_PROVA]
ORDER BY idQuestao DESC;

-- Verificar alternativas da questão
SELECT * FROM alternativa
WHERE idQuestao = [ID_DA_QUESTAO];
```

---

## 📝 PASSO 7: Compartilhar o Log

Se o problema persistir após seguir todos os passos:

### Opção 1 - Via navegador:
1. Acesse `ver_logs.php`
2. Clique em "Download" no arquivo de hoje
3. Abra o arquivo `.txt` e me envie o conteúdo

### Opção 2 - Via FTP:
1. Baixe: `/public_html/logs_javascript_YYYY-MM-DD.txt`
2. Abra no Notepad
3. Me envie o conteúdo completo

---

## 🎯 Diferenças da Versão FINAL

### ✅ O que foi ADICIONADO:
- Sistema de log automático (tudo é registrado)
- Visualizador de logs bonito
- Mensagens mais claras de erro
- Suporte para múltiplos formatos de resposta do servidor

### ❌ O que foi REMOVIDO:
- Campo de anexo (upload de arquivo)
- Código relacionado a `$_FILES`
- Validação de arquivo

### 🔄 O que foi MANTIDO:
- Toda a lógica de salvamento
- Validações de campos
- Alternativas dinâmicas
- Questões objetivas e discursivas
- Sistema de sessão para idProva

---

## 🆘 Troubleshooting Rápido

| Sintoma | Causa Provável | Solução |
|---------|----------------|---------|
| Página não carrega | Arquivo não foi enviado | Verificar upload |
| "Questão salva" mas não aparece no banco | Backend funciona, mas sessão perdida | Verificar cookies do navegador |
| Log vazio | `salvar_log_js.php` não funciona | Verificar permissões de escrita |
| Erro 500 | Erro de sintaxe no PHP | Ver logs do Apache |
| Botão não responde | JavaScript não carregou | Verificar `js/criarProva_FINAL.js` |

---

## 📞 Suporte

Se após seguir TODOS os passos o problema persistir:

**Me envie:**
1. ✅ Conteúdo completo do log (`logs_javascript_YYYY-MM-DD.txt`)
2. ✅ Screenshot da mensagem de erro (se houver)
3. ✅ Confirmação de que os 4 arquivos foram enviados
4. ✅ Resultado da consulta SQL no banco (última prova)

**NÃO precisa enviar:**
- ❌ Console do Chrome (já temos o log automático!)
- ❌ Código PHP/JS (já temos os arquivos)

---

## ✅ Checklist Final

Antes de me reportar um problema, confirme:

- [ ] Os 4 arquivos foram enviados via FTP
- [ ] A página `cadastroProva2_FINAL.php` abre sem erro
- [ ] Você preencheu o formulário completamente
- [ ] Você clicou em "SALVAR QUESTÃO"
- [ ] Você acessou `ver_logs.php`
- [ ] Você verificou se existe arquivo de log de hoje
- [ ] Você leu o conteúdo do log
- [ ] Você verificou o banco de dados (opcional)

---

**Data:** 2025-10-01
**Versão:** FINAL 1.0
**Status:** Pronto para produção

🎉 **Boa sorte com os testes!**
