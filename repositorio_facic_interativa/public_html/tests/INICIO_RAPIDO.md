# ⚡ Guia de Início Rápido - Testes Playwright

## 🎯 Para Desenvolvedores que Nunca Usaram Playwright

### 1️⃣ Instalação (5 minutos)

```bash
# 1. Navegue até a pasta
cd D:\SERVIDOR\01.projetos\interativa\01_ambiente_teste_interativa\repositorio_facic_interativa\tests

# 2. Instale dependências
npm install

# 3. Instale navegadores
npx playwright install
```

### 2️⃣ Configuração (2 minutos)

```bash
# 1. Copie o arquivo de exemplo
copy .env.example .env

# 2. Edite com suas credenciais
notepad .env
```

**Mínimo necessário no `.env`:**
```env
BASE_URL=http://localhost/facic_interativa
ALUNO_LOGIN=seu_aluno@teste.com
ALUNO_SENHA=SuaSenha123
```

### 3️⃣ Primeiro Teste (1 minuto)

```bash
# Execute um teste simples
npx playwright test alunos.spec.js --headed
```

✅ Se ver o navegador abrindo e executando ações, está funcionando!

---

## 🚀 Comandos Essenciais

### Executar Testes

```bash
# Todos os testes (modo invisível)
npm test

# Ver o navegador funcionando
npm run test:headed

# Interface gráfica (recomendado para iniciantes)
npm run test:ui

# Modo debug (passo a passo)
npm run test:debug
```

### Ver Resultados

```bash
# Abrir relatório HTML
npm run report
```

---

## 🎓 Entendendo um Teste Básico

```javascript
// 1. Importações
const { test, expect } = require('@playwright/test');
const { fazerLogin } = require('../helpers');

// 2. Grupo de testes
test.describe('Meu Grupo de Testes', () => {
  
  // 3. Antes de cada teste
  test.beforeEach(async ({ page }) => {
    await fazerLogin(page, 'aluno');
  });

  // 4. Um teste
  test('Deve fazer algo', async ({ page }) => {
    // Navegar para página
    await page.goto('/pagina.php');
    
    // Preencher campo
    await page.fill('input[name="campo"]', 'valor');
    
    // Clicar botão
    await page.click('button[type="submit"]');
    
    // Verificar resultado
    await expect(page.locator('.sucesso')).toBeVisible();
  });
});
```

---

## 🔍 Encontrando Elementos na Página

### Seletores Mais Comuns

```javascript
// Por nome
page.fill('input[name="email"]', 'teste@email.com')

// Por ID
page.click('#btn-enviar')

// Por classe
page.locator('.alert-success')

// Por texto
page.click('button:has-text("Enviar")')

// Por tipo
page.fill('input[type="password"]', '123456')
```

### Ações Comuns

```javascript
// Navegar
await page.goto('/pagina.php')

// Preencher texto
await page.fill('input[name="nome"]', 'João')

// Clicar
await page.click('button#enviar')

// Selecionar dropdown
await page.selectOption('select[name="turma"]', '1')

// Upload de arquivo
await page.setInputFiles('input[type="file"]', 'arquivo.pdf')

// Esperar elemento
await page.waitForSelector('.mensagem')

// Verificar texto
await expect(page.locator('.titulo')).toHaveText('Sucesso')

// Verificar visibilidade
await expect(page.locator('.modal')).toBeVisible()
```

---

## 🐛 Problemas Comuns e Soluções

### ❌ "Error: Browser closed unexpectedly"

**Solução:**
```bash
npx playwright install --force
```

### ❌ "Timeout waiting for element"

**Causa:** Elemento não existe ou demora para aparecer

**Solução:**
```javascript
// Aumentar timeout
await page.waitForSelector('.elemento', { timeout: 10000 })

// Ou verificar se seletor está correto
```

### ❌ "Cannot find module 'helpers'"

**Causa:** Caminho do import incorreto

**Solução:**
```javascript
// Use caminho relativo correto
const { fazerLogin } = require('../helpers')
```

### ❌ Teste passa mas não deveria

**Causa:** Asserção errada ou não executada

**Solução:**
```javascript
// Use await nas verificações
await expect(page.locator('.erro')).toBeVisible()
//    ^^^^^ não esqueça o await!
```

---

## 📊 Interpretando Resultados

### ✅ Teste Passou

```
✓ CT-EMAIL-001: Deve disparar email ao aluno (2.3s)
```

### ❌ Teste Falhou

```
✗ CT-EMAIL-002: Email deve conter dados corretos (5.1s)
  Timeout 5000ms exceeded.
  waiting for locator('.alert-success')
```

**O que fazer:**
1. Abrir relatório HTML: `npm run report`
2. Ver screenshot da falha
3. Ver trace da execução
4. Corrigir problema
5. Rodar novamente

---

## 🎯 Fluxo de Trabalho Recomendado

### Para Investigar Problema de Email

1. **Execute teste específico:**
   ```bash
   npx playwright test CT-EMAIL-001 --headed
   ```

2. **Veja o navegador em ação**

3. **Se falhar, veja o relatório:**
   ```bash
   npm run report
   ```

4. **Debug se necessário:**
   ```bash
   npx playwright test CT-EMAIL-001 --debug
   ```

### Para Criar Novo Teste

1. **Copie template de teste existente**

2. **Modifique para seu caso:**
   - Mude o ID do teste
   - Ajuste navegação
   - Ajuste ações
   - Ajuste verificações

3. **Execute isoladamente:**
   ```bash
   npx playwright test seu-novo-teste
   ```

4. **Se funcionar, adicione ao grupo**

---

## 🛠️ Ferramentas Úteis

### Interface Gráfica (Melhor para Aprender)

```bash
npm run test:ui
```

**Recursos:**
- Ver todos os testes
- Executar seletivamente
- Ver resultados em tempo real
- Ver screenshots/vídeos

### Gravador de Testes (Codegen)

```bash
npx playwright codegen http://localhost/facic_interativa
```

**Gera código automaticamente** enquanto você:
1. Navega pelo site
2. Clica em elementos
3. Preenche formulários

### Inspector

```bash
npm run test:debug
```

**Permite:**
- Pausar em breakpoints
- Executar passo a passo
- Inspecionar elementos
- Testar seletores

---

## 📚 Próximos Passos

1. ✅ Executou primeiro teste com sucesso
2. ⬜ Execute todos os testes: `npm test`
3. ⬜ Veja relatório: `npm run report`
4. ⬜ Crie seu primeiro teste modificando um existente
5. ⬜ Leia documentação completa: `README.md`
6. ⬜ Estude mapeamento de problemas: `MAPEAMENTO_PROBLEMAS.md`

---

## 🆘 Ajuda Adicional

### Documentação Oficial
- [Playwright Docs](https://playwright.dev)
- [Getting Started](https://playwright.dev/docs/intro)
- [API Reference](https://playwright.dev/docs/api/class-playwright)

### Nossos Documentos
- `README.md` - Documentação completa
- `MAPEAMENTO_PROBLEMAS.md` - Relação problema x teste
- `helpers.js` - Funções auxiliares disponíveis

### Exemplos Práticos
- `emails/alunos.spec.js` - Testes de alunos
- `emails/professores.spec.js` - Testes de professores
- `emails/admin.spec.js` - Testes de admin

---

**💡 Dica Final:** Comece com `npm run test:ui` - é a forma mais fácil de entender como tudo funciona!

**🎯 Foco Inicial:** Execute `npm run test:alunos` para verificar o problema principal de emails não recebidos.

---

**Última atualização:** Outubro 2025
