# 🧪 Testes Automatizados - FACIC Interativa

## 📋 Sobre

Suite completa de testes automatizados usando **Playwright** para o sistema FACIC Interativa, com foco especial no **sistema de envio de emails**.

### 🎯 Problemas Endereçados

Estes testes foram criados para diagnosticar e prevenir os seguintes problemas reportados:

1. ✉️ **Alunos não recebendo emails** após envio de atividades
2. 📝 **Provas não aparecendo** em `visualizarProvas2.php`
3. ❓ **Questões não populando** ao criar provas
4. 🔄 **Arquivos duplicados** (`aplicarProva.php` vs `aplicarProva2.php`)

---

## 🚀 Instalação

### Pré-requisitos

- Node.js 16+ instalado
- Sistema FACIC rodando localmente ou em servidor de testes

### Passo a Passo

1. **Navegue até a pasta de testes:**
   ```bash
   cd tests
   ```

2. **Instale as dependências:**
   ```bash
   npm install
   ```

3. **Instale os navegadores do Playwright:**
   ```bash
   npx playwright install
   ```

4. **Configure as variáveis de ambiente:**
   ```bash
   cp .env.example .env
   # Edite o arquivo .env com suas configurações
   ```

---

## ⚙️ Configuração

### Arquivo `.env`

Crie um arquivo `.env` na pasta `tests` com as seguintes variáveis:

```env
# URL do sistema
BASE_URL=http://localhost/facic_interativa

# Credenciais de teste - ALUNO
ALUNO_LOGIN=aluno.teste@facic.com.br
ALUNO_SENHA=Teste123!
ALUNO_RA=2024001

# Credenciais de teste - PROFESSOR
PROFESSOR_LOGIN=professor.teste@facic.com.br
PROFESSOR_SENHA=Prof123!

# Credenciais de teste - ADMIN
ADMIN_LOGIN=admin@facic.com.br
ADMIN_SENHA=Admin123!

# Configuração SMTP (para validação)
SMTP_HOST=email-ssl.com.br
SMTP_PORT=993
SMTP_USER=tecnologia@sitefacic.institucional.ws
```

---

## 🧪 Executando os Testes

### Todos os Testes

```bash
npm test
```

### Testes Específicos

```bash
# Apenas testes de emails de alunos
npm run test:alunos

# Apenas testes de emails de professores
npm run test:professores

# Apenas testes de emails de admin
npm run test:admin

# Todos os testes de email
npm run test:emails
```

### Modo Interativo (UI)

```bash
npm run test:ui
```

### Modo Debug

```bash
npm run test:debug
```

### Com Interface Gráfica

```bash
npm run test:headed
```

---

## 📊 Relatórios

Após executar os testes, visualize o relatório HTML:

```bash
npm run report
```

Os relatórios são gerados automaticamente em:
- `playwright-report/` - Relatório HTML
- `test-results.json` - Resultados em JSON

---

## 📁 Estrutura de Testes

```
tests/
├── package.json              # Configurações npm
├── playwright.config.js      # Configuração Playwright
├── helpers.js                # Funções auxiliares
├── .env                      # Variáveis de ambiente (não versionado)
├── .env.example              # Exemplo de variáveis
│
└── emails/                   # Testes de email
    ├── alunos.spec.js        # Testes para alunos (FOCO PRINCIPAL)
    ├── professores.spec.js   # Testes para professores
    └── admin.spec.js         # Testes para administradores
```

---

## 🎯 Casos de Teste Críticos

### Para Alunos (Principal)

| ID | Descrição | Prioridade |
|----|-----------|------------|
| **CT-EMAIL-001** | Disparar email após envio de atividade | 🔴 CRÍTICO |
| **CT-EMAIL-002** | Verificar conteúdo do email | 🔴 CRÍTICO |
| **CT-EMAIL-003** | Disparo duplo (aluno + professor) | 🔴 CRÍTICO |
| **CT-EMAIL-004** | Configuração SMTP correta | 🔴 CRÍTICO |
| **CT-EMAIL-005** | Timeout de envio (<10s) | 🟡 IMPORTANTE |

### Para Professores

| ID | Descrição | Prioridade |
|----|-----------|------------|
| **CT-EMAIL-PROF-011** | Prova aparece em visualizarProvas2.php | 🔴 CRÍTICO |
| **CT-EMAIL-PROF-013** | Questões aparecem ao criar prova | 🔴 CRÍTICO |
| **CT-EMAIL-PROF-014** | Turmas aparecem ao criar prova | 🔴 CRÍTICO |

### Para Admin

| ID | Descrição | Prioridade |
|----|-----------|------------|
| **CT-EMAIL-ADM-024** | Verificar config SMTP atual | 🔴 CRÍTICO |
| **CT-DIAG-ADM-003** | Taxa de falha < 5% | 🟡 IMPORTANTE |

---

## 🔍 Diagnóstico de Problemas

### Problema: Alunos não recebem emails

**Testes relacionados:**
- `CT-EMAIL-001` até `CT-EMAIL-007`
- `CT-DIAG-001` até `CT-DIAG-003`

**Possíveis causas investigadas:**
1. Configuração SMTP incorreta
2. Timeout no envio
3. Função `enviarEmail.php` não sendo chamada
4. Emails não sendo gravados no banco
5. Servidor SMTP bloqueado

### Problema: Provas não aparecem

**Testes relacionados:**
- `CT-EMAIL-PROF-011`
- `CT-EMAIL-PROF-013`
- `CT-EMAIL-PROF-014`

**Possíveis causas investigadas:**
1. Query SQL incorreta
2. Filtros muito restritivos
3. Problema no relacionamento turma/disciplina
4. Cache do navegador

### Problema: Arquivos duplicados

**Testes relacionados:**
- `CT-EMAIL-PROF-012`

**Investigação:**
- Verificar se ambos `aplicarProva.php` e `aplicarProva2.php` estão ativos
- Identificar qual é o correto
- Documentar diferenças

---

## 🛠️ Desenvolvimento de Novos Testes

### Template Básico

```javascript
const { test, expect } = require('@playwright/test');
const { fazerLogin } = require('../helpers');

test.describe('Nome do Grupo de Testes', () => {
  
  test.beforeEach(async ({ page }) => {
    await fazerLogin(page, 'aluno'); // ou 'professor', 'admin'
  });

  test('CT-XXX-001: Descrição do teste', async ({ page }) => {
    // Arrange (preparar)
    await page.goto('/pagina.php');
    
    // Act (agir)
    await page.fill('input[name="campo"]', 'valor');
    await page.click('button[type="submit"]');
    
    // Assert (verificar)
    await expect(page.locator('.mensagem-sucesso')).toBeVisible();
  });
});
```

---

## 📝 Convenções

### Nomenclatura de Testes

- **CT-EMAIL-XXX**: Casos de teste de email
- **CT-DIAG-XXX**: Casos de diagnóstico
- **CT-EMAIL-PROF-XXX**: Específicos de professores
- **CT-EMAIL-ADM-XXX**: Específicos de admin

### Prioridades

- 🔴 **CRÍTICO**: Funcionalidade essencial, deve passar sempre
- 🟡 **IMPORTANTE**: Funcionalidade relevante, mas não bloqueante
- 🟢 **NORMAL**: Funcionalidade desejável

---

## 🐛 Troubleshooting

### Testes falhando com timeout

```bash
# Aumentar timeout global
npm test -- --timeout=60000
```

### Navegador não abre

```bash
# Reinstalar navegadores
npx playwright install --force
```

### Erro de conexão com sistema

Verificar se:
1. Sistema está rodando
2. URL em `.env` está correta
3. Firewall não está bloqueando

---

## 📚 Recursos Úteis

- [Documentação Playwright](https://playwright.dev)
- [Seletores CSS](https://www.w3schools.com/cssref/css_selectors.asp)
- [Asserções Playwright](https://playwright.dev/docs/test-assertions)

---

## 🤝 Contribuindo

Para adicionar novos testes:

1. Identifique o problema ou funcionalidade
2. Crie o teste seguindo o template
3. Documente o caso de teste
4. Execute e valide
5. Commit com mensagem descritiva

---

## 📞 Suporte

Para problemas ou dúvidas:
- Consulte a documentação acima
- Verifique os logs em `playwright-report/`
- Revise os arquivos `test-results.json`

---

**Última atualização:** Outubro 2025  
**Versão:** 1.0.0
