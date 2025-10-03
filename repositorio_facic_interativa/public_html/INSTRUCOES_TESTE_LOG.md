# 🔍 INSTRUÇÕES PARA TESTE COM LOGS DETALHADOS

## Arquivos Criados

1. **gravarProva2_LOG.php** - Versão do backend com logs completos
2. **criarProva_LOG.js** - Versão do JavaScript com logs no console
3. **cadastroProva2_LOG.php** - Página de teste completa
4. **INSTRUCOES_TESTE_LOG.md** - Este arquivo

## Como Testar

### Passo 1: Upload dos Arquivos
Faça upload dos seguintes arquivos para o servidor:
- `gravarProva2_LOG.php`
- `js/criarProva_LOG.js`
- `cadastroProva2_LOG.php`

### Passo 2: Acessar a Página de Teste
Acesse: **http://www.facicinterativa.com.br/ambiente_QA/cadastroProva2_LOG.php**

### Passo 3: Abrir o Console do Navegador
- **Chrome/Edge**: Pressione `F12` ou `Ctrl+Shift+J`
- **Firefox**: Pressione `F12` ou `Ctrl+Shift+K`
- Vá para a aba "Console"

### Passo 4: Preencher o Formulário
1. Digite um título para a prova
2. Escreva uma descrição da questão
3. Preencha as alternativas (se objetiva)
4. Marque a alternativa correta
5. (Opcional) Anexe um arquivo

### Passo 5: Clicar em "SALVAR QUESTÃO"
- O console mostrará todos os passos do processo
- Um alerta aparecerá com o nome do arquivo de log

### Passo 6: Verificar os Logs

#### No Console do Navegador:
Você verá logs detalhados de cada etapa:
```
========================================
INICIANDO SALVAMENTO - criarProva_LOG.js
========================================
1. VALIDAÇÃO INICIAL
Título válido: true
Descrição válida: true
...
```

#### No Servidor:
Um arquivo será criado com o formato: `log_gravar_prova_YYYY-MM-DD_HH-MM-SS.txt`

Para visualizar o log do servidor, acesse:
**http://www.facicinterativa.com.br/ambiente_QA/log_gravar_prova_XXXX.txt**

(Substitua XXXX pelo nome exato do arquivo que apareceu no alerta)

## O Que os Logs Mostram

### Logs do JavaScript (Console):
1. ✅ Validação dos campos
2. ✅ Preparação do FormData
3. ✅ Envio da requisição
4. ✅ Resposta do servidor
5. ❌ Erros (se houver)

### Logs do PHP (Arquivo .txt):
1. ✅ Verificação de sessão
2. ✅ Dados recebidos ($_POST, $_FILES)
3. ✅ Validação de campos
4. ✅ Criação/recuperação da prova
5. ✅ Processamento de alternativas
6. ✅ Inserção da questão
7. ✅ Inserção das alternativas
8. ✅ Upload de anexo (se houver)
9. ✅ Queries SQL executadas
10. ❌ Erros detalhados (se houver)

## Interpretando os Resultados

### ✅ SUCESSO
Se tudo funcionar:
- Aparecerá um alerta: "✅ SUCESSO! Questão salva."
- O console mostrará logs com ✅
- O arquivo .txt mostrará "SUCESSO TOTAL"

### ❌ ERRO
Se houver erro:
- Aparecerá um alerta com a mensagem de erro
- O console mostrará logs com ❌
- O arquivo .txt mostrará exatamente onde o erro ocorreu

## Cenários de Teste

### Teste 1: Questão Objetiva Simples
- Título: "Teste de Matemática"
- Descrição: "Quanto é 2+2?"
- Alternativa A: "3"
- Alternativa B: "4" ← marcar como correta
- Peso: 1

### Teste 2: Questão Discursiva
- Título: "Teste de Português"
- Descrição: "Explique o que é uma metáfora"
- Tipo: Discursiva
- Peso: 2

### Teste 3: Questão com Anexo
- Preencher campos normalmente
- Anexar uma imagem ou PDF

### Teste 4: Múltiplas Questões
- Salvar várias questões seguidas
- Verificar se o idProva é mantido na sessão

## Comparação com Versão Normal

Após diagnosticar o problema com a versão LOG, você poderá:
1. Identificar exatamente onde está o erro
2. Aplicar a correção na versão normal (cadastroProva2.php)
3. Testar novamente

## Estrutura dos Arquivos de Log

```
[2025-10-01 14:30:45] ========================================
[2025-10-01 14:30:45] INÍCIO DO PROCESSAMENTO
[2025-10-01 14:30:45] ========================================
[2025-10-01 14:30:45] 1. VERIFICAÇÃO DE SESSÃO
[2025-10-01 14:30:45] Session ID: abc123...
[2025-10-01 14:30:45] Session usuario: professor@email.com
[2025-10-01 14:30:45] ✅ Sessão validada com sucesso
...
```

## Próximos Passos

1. **Execute o teste** com a página LOG
2. **Compartilhe os logs** (console + arquivo .txt)
3. **Analisaremos juntos** onde está o problema
4. **Aplicaremos a correção** no arquivo original
5. **Testaremos novamente** até funcionar 100%

## Observações Importantes

- ⚠️ Os arquivos LOG ficam no mesmo diretório do projeto
- ⚠️ Cada teste cria um novo arquivo de log
- ⚠️ Lembre-se de deletar os logs antigos periodicamente
- ⚠️ NÃO use a versão LOG em produção (apenas para debug)

## Contato

Se encontrar qualquer erro ou precisar de ajuda para interpretar os logs, me avise e compartilhe:
1. O conteúdo do console (screenshot ou texto)
2. O conteúdo do arquivo .txt gerado
3. O que você estava tentando fazer quando o erro ocorreu

---

**Data de criação:** 01/10/2025
**Versão:** 1.0
**Status:** Pronto para testes
