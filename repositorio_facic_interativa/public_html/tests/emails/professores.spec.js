const { test, expect } = require('@playwright/test');
const { fazerLogin, USUARIOS_TESTE, URLS } = require('../helpers');

/**
 * TESTES DE EMAILS PARA PROFESSORES
 * 
 * Baseado no mapeamento do sistema, estes testes cobrem:
 * 1. Notificações de atividades entregues
 * 2. Alertas de prazos
 * 3. Notificações de sistema
 */

test.describe('Emails para Professores - Notificações de Atividades', () => {
  
  test.beforeEach(async ({ page }) => {
    await fazerLogin(page, 'professor');
  });

  /**
   * TESTE CRÍTICO: Professor deve receber email quando aluno envia atividade
   * Este é o par do CT-EMAIL-003 (disparo duplo)
   */
  test('CT-EMAIL-PROF-001: Deve notificar professor quando aluno enviar atividade', async ({ page }) => {
    // Simular que um aluno enviou atividade
    // Este teste complementa o CT-EMAIL-003
    
    // Na prática, quando aluno envia atividade via gravarAtividade.php,
    // o professor deve receber notificação
    
    // TODO: Verificar logs ou banco de dados
    // Verificar que professor recebeu email com:
    // - Nome do aluno
    // - Disciplina
    // - Data/hora de envio
    // - Link para correção
  });

  /**
   * TESTE: Email ao receber múltiplas atividades
   */
  test('CT-EMAIL-PROF-002: Deve agrupar notificações se múltiplas atividades forem enviadas', async ({ page }) => {
    // Verificar se sistema envia um email com resumo ou múltiplos emails
    // Ideal: Um email com lista de atividades se enviadas em período curto
  });

  /**
   * TESTE: Confirmação de correção de atividade
   */
  test('CT-EMAIL-PROF-003: Deve confirmar quando professor corrigir atividade', async ({ page }) => {
    await page.goto('/corrigirAtividade.php?id=1');
    
    await page.fill('textarea[name="feedback"]', 'Ótimo trabalho!');
    await page.fill('input[name="nota"]', '9.5');
    await page.click('button[type="submit"]');
    
    await expect(page.locator('.alert-success')).toBeVisible();
    
    // Professor deve receber confirmação
    // Aluno deve receber notificação de nota
  });
});

test.describe('Emails para Professores - Provas e Avaliações', () => {
  
  /**
   * TESTE: Confirmação de criação de prova
   */
  test('CT-EMAIL-PROF-010: Deve confirmar criação de nova prova', async ({ page }) => {
    await fazerLogin(page, 'professor');
    await page.goto(URLS.cadastroProva);
    
    await page.fill('input[name="titulo"]', 'Prova Final 2025');
    await page.fill('textarea[name="descricao"]', 'Prova final do semestre');
    await page.fill('input[name="data"]', '2025-12-20');
    await page.selectOption('select[name="turma"]', '1');
    
    await page.click('button[type="submit"]');
    
    await expect(page.locator('.alert-success')).toBeVisible();
    
    // Professor deve receber email de confirmação
    // Email deve conter: título, data, turma, link para gerenciar
  });

  /**
   * TESTE CRÍTICO: Problema reportado - Provas não aparecem em visualizarProvas2.php
   * Este teste verifica se após criar prova, ela aparece na listagem
   */
  test('CT-EMAIL-PROF-011: Prova criada deve aparecer em visualizarProvas2.php', async ({ page }) => {
    await fazerLogin(page, 'professor');
    
    // 1. Criar prova
    await page.goto(URLS.cadastroProva);
    const tituloProva = `Prova Teste ${Date.now()}`;
    await page.fill('input[name="titulo"]', tituloProva);
    await page.fill('input[name="data"]', '2025-12-31');
    await page.click('button[type="submit"]');
    
    await expect(page.locator('.alert-success')).toBeVisible();
    
    // 2. Verificar se aparece na listagem
    await page.goto(URLS.visualizarProvas);
    
    // A prova deve aparecer na lista
    const provaVisivel = await page.locator(`text=${tituloProva}`).count();
    expect(provaVisivel).toBeGreaterThan(0);
    
    // Se não aparecer, há problema no banco ou no SELECT
  });

  /**
   * TESTE: Verificar diferença entre aplicarProva.php e aplicarProva2.php
   * Problema reportado: existem dois arquivos
   */
  test('CT-EMAIL-PROF-012: Verificar qual arquivo de aplicarProva está ativo', async ({ page }) => {
    await fazerLogin(page, 'professor');
    
    // Testar aplicarProva.php
    const response1 = await page.goto('/aplicarProva.php');
    const status1 = response1?.status();
    
    // Testar aplicarProva2.php
    const response2 = await page.goto('/aplicarProva2.php');
    const status2 = response2?.status();
    
    // Ambos devem retornar 200 ou apenas um?
    console.log(`aplicarProva.php: ${status1}`);
    console.log(`aplicarProva2.php: ${status2}`);
    
    // TODO: Verificar qual está sendo usado no sistema
    // Se ambos estão ativos, pode haver conflito
  });

  /**
   * TESTE CRÍTICO: Questões não aparecem ao criar prova
   * Problema reportado pelos professores
   */
  test('CT-EMAIL-PROF-013: Questões cadastradas devem aparecer ao criar prova', async ({ page }) => {
    await fazerLogin(page, 'professor');
    await page.goto(URLS.cadastroProva);
    
    // Clicar em "Adicionar Questão" ou similar
    await page.click('button#adicionar-questao, a:has-text("Adicionar Questão")');
    
    // Lista de questões deve aparecer
    const questoes = await page.locator('select[name="questao"], .lista-questoes .questao').count();
    
    // Deve haver pelo menos uma questão cadastrada
    expect(questoes).toBeGreaterThan(0);
    
    // Se não houver, problema pode ser:
    // 1. SELECT não está trazendo questões do banco
    // 2. Filtro por disciplina está muito restritivo
    // 3. Questões não estão sendo inseridas corretamente
  });

  /**
   * TESTE CRÍTICO: Turmas não aparecem ao criar prova
   */
  test('CT-EMAIL-PROF-014: Turmas devem aparecer ao criar prova', async ({ page }) => {
    await fazerLogin(page, 'professor');
    await page.goto(URLS.cadastroProva);
    
    // Select de turmas deve ter opções
    const turmas = await page.locator('select[name="turma"] option').count();
    
    // Deve haver pelo menos uma turma (excluindo opção vazia)
    expect(turmas).toBeGreaterThan(1);
    
    // Se não aparecer turmas, verificar:
    // 1. Professor está vinculado a turmas?
    // 2. Query está filtrando por professor corretamente?
    // 3. Tabela de turmas está populada?
  });
});

test.describe('Emails para Professores - Alertas e Lembretes', () => {
  
  /**
   * TESTE: Alerta de prazo próximo
   */
  test('CT-EMAIL-PROF-020: Deve alertar professor sobre prazos próximos', async ({ page }) => {
    // Professor deve receber email alertando sobre:
    // - Atividades não corrigidas há mais de X dias
    // - Provas próximas a vencer
    // - Alunos com atividades atrasadas
  });

  /**
   * TESTE: Resumo semanal
   */
  test('CT-EMAIL-PROF-021: Deve enviar resumo semanal de atividades', async ({ page }) => {
    // Email semanal com:
    // - Número de atividades recebidas
    // - Número de atividades corrigidas  
    // - Pendências
  });
});

test.describe('Emails para Professores - Diagnóstico', () => {
  
  /**
   * TESTE DIAGNÓSTICO: Verificar consulta SQL de questões
   */
  test('CT-DIAG-PROF-001: Query de questões deve retornar resultados', async ({ page }) => {
    await fazerLogin(page, 'professor');
    
    // TODO: Criar endpoint que mostra SQL usado para buscar questões
    // Verificar se filtros estão corretos:
    // - Disciplina do professor
    // - Status ativo
    // - Tipo de questão
  });

  /**
   * TESTE DIAGNÓSTICO: Verificar permissões do professor
   */
  test('CT-DIAG-PROF-002: Professor deve ter permissão para criar provas', async ({ page }) => {
    await fazerLogin(page, 'professor');
    await page.goto(URLS.cadastroProva);
    
    // Não deve redirecionar para login ou erro de permissão
    expect(page.url()).toContain('cadastrarProva');
    
    // Formulário deve estar visível
    await expect(page.locator('form')).toBeVisible();
  });
});
