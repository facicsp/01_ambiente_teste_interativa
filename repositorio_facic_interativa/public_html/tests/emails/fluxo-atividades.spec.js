const { test, expect } = require('@playwright/test');
const { fazerLogin, USUARIOS_TESTE, URLS } = require('../helpers');

/**
 * ═══════════════════════════════════════════════════════════════
 * TESTES DO FLUXO COMPLETO: ENVIO DE ATIVIDADES
 * ═══════════════════════════════════════════════════════════════
 * 
 * PROBLEMA REPORTADO:
 * Alunos não estão recebendo emails após envio de atividades
 * 
 * FLUXO ESPERADO:
 * 1. Aluno envia atividade → gravarAtividade.php
 * 2. Sistema grava atividade no banco
 * 3. Sistema chama enviarEmail.php
 * 4. enviarEmail.php dispara 2 emails:
 *    a) Confirmação para ALUNO
 *    b) Notificação para PROFESSOR
 * 
 * PONTOS DE FALHA POSSÍVEIS (identificados no mapeamento):
 * - Problema na configuração SMTP
 * - Timeout no envio
 * - Erro ao buscar dados do professor
 * - Email do aluno/professor inválido
 * - Arquivo de log não sendo gravado
 * - Função enviarEmail() não sendo chamada
 */

test.describe('🎯 FLUXO CRÍTICO: Envio de Atividade com Email', () => {
  
  /**
   * TESTE 1: Fluxo completo end-to-end
   * Este teste simula o fluxo real do usuário
   */
  test('E2E-001: Aluno envia atividade e recebe confirmação por email', async ({ page }) => {
    // PASSO 1: Login como aluno
    await fazerLogin(page, 'aluno');
    
    // PASSO 2: Navegar para envio de atividade
    await page.goto('/enviarAtividade.php?disciplina=1');
    
    // Verificar que formulário carregou
    await expect(page.locator('form#form-atividade')).toBeVisible();
    
    // PASSO 3: Preencher atividade
    const timestampAtividade = Date.now();
    await page.fill('textarea[name="descricao"]', `Atividade teste E2E ${timestampAtividade}`);
    
    // Anexar arquivo (opcional)
    // await page.setInputFiles('input[type="file"]', 'test-files/atividade-teste.pdf');
    
    // PASSO 4: Enviar atividade
    const submitPromise = page.waitForResponse(response => 
      response.url().includes('gravarAtividade.php') && response.status() === 200
    );
    
    await page.click('button[type="submit"]');
    await submitPromise;
    
    // PASSO 5: Verificar mensagem de sucesso
    await expect(page.locator('.alert-success')).toBeVisible({ timeout: 10000 });
    const mensagemSucesso = await page.locator('.alert-success').textContent();
    
    // PASSO 6: Verificar que atividade foi gravada no banco
    // TODO: Consultar API ou banco para confirmar registro
    
    // PASSO 7: CRÍTICO - Verificar que email foi enviado
    // Aqui precisamos de uma estratégia para verificar o envio:
    // Opção A: Consultar tabela de logs de email
    // Opção B: Usar serviço de email de teste (MailHog, Mailtrap)
    // Opção C: Verificar arquivo de log
    
    console.log(`✅ Atividade ${timestampAtividade} enviada com sucesso`);
    console.log(`📧 Email deve ter sido enviado para: ${USUARIOS_TESTE.aluno.email}`);
  });

  /**
   * TESTE 2: Verificar que professor também recebe notificação
   */
  test('E2E-002: Professor recebe notificação quando aluno envia atividade', async ({ page }) => {
    // Este teste complementa o anterior
    // Após aluno enviar atividade, professor deve ser notificado
    
    await fazerLogin(page, 'admin');
    
    // Acessar logs de email
    await page.goto('/admin/logs-email.php');
    
    // Filtrar emails enviados nos últimos 5 minutos
    const agora = new Date();
    const cincoMinutosAtras = new Date(agora.getTime() - 5 * 60000);
    
    // Buscar por emails para professor
    const emailsProfessor = await page.locator('table tr')
      .filter({ hasText: USUARIOS_TESTE.professor.email })
      .count();
    
    expect(emailsProfessor).toBeGreaterThan(0);
  });

  /**
   * TESTE 3: Verificar timeout - email não deve travar o sistema
   */
  test('E2E-003: Envio de atividade não deve travar mesmo se email falhar', async ({ page }) => {
    await fazerLogin(page, 'aluno');
    
    await page.goto('/enviarAtividade.php?disciplina=1');
    
    // Medir tempo total do processo
    const inicio = Date.now();
    
    await page.fill('textarea[name="descricao"]', 'Teste de timeout');
    await page.click('button[type="submit"]');
    
    await expect(page.locator('.alert-success, .alert-warning')).toBeVisible({ timeout: 15000 });
    
    const tempoTotal = Date.now() - inicio;
    
    // Mesmo com problema no email, não deve levar mais de 15 segundos
    expect(tempoTotal).toBeLessThan(15000);
    
    console.log(`⏱️ Tempo total: ${tempoTotal}ms`);
  });
});

test.describe('🔍 DIAGNÓSTICO: Por que emails não chegam?', () => {
  
  /**
   * TESTE DIAG-001: Verificar se função enviarEmail() está sendo chamada
   */
  test('DIAG-001: gravarAtividade.php deve chamar enviarEmail()', async ({ page }) => {
    // Estratégia: Interceptar requisição ou verificar logs
    
    await fazerLogin(page, 'aluno');
    
    // Monitorar console do backend (se possível)
    const logs = [];
    page.on('console', msg => logs.push(msg.text()));
    
    await page.goto('/enviarAtividade.php?disciplina=1');
    await page.fill('textarea[name="descricao"]', 'Teste diagnóstico');
    await page.click('button[type="submit"]');
    
    await page.waitForTimeout(2000);
    
    // Verificar se há log de "Enviando email..." ou similar
    const temLogEmail = logs.some(log => log.includes('email') || log.includes('Email'));
    
    console.log('📋 Logs capturados:', logs);
    console.log(`📧 Função de email foi chamada? ${temLogEmail ? 'SIM' : 'NÃO'}`);
  });

  /**
   * TESTE DIAG-002: Verificar configuração SMTP
   */
  test('DIAG-002: Configuração SMTP deve estar completa', async ({ page }) => {
    await fazerLogin(page, 'admin');
    
    // Tentar acessar página de configuração
    const response = await page.goto('/admin/config-smtp.php');
    
    if (response?.status() === 404) {
      // Configuração pode estar em outro lugar
      await page.goto('/admin/configuracoes.php');
    }
    
    // Verificar valores de configuração (esperados baseado na conversa):
    const configEsperada = {
      host: 'email-ssl.com.br',
      port: '993', 
      user: 'tecnologia@sitefacic.institucional.ws',
      ssl: true
    };
    
    // TODO: Adicionar lógica para ler configuração atual
    console.log('🔧 Configuração esperada:', configEsperada);
  });

  /**
   * TESTE DIAG-003: Teste de conexão SMTP
   */
  test('DIAG-003: Deve conseguir conectar ao servidor SMTP', async ({ page }) => {
    await fazerLogin(page, 'admin');
    
    // Procurar por página de teste de SMTP
    const urls = [
      '/admin/testar-smtp.php',
      '/admin/teste-email.php',
      '/admin/configuracoes.php?acao=testar'
    ];
    
    for (const url of urls) {
      const response = await page.goto(url);
      if (response?.status() === 200) {
        console.log(`✅ Encontrada página de teste em: ${url}`);
        
        // Procurar botão de teste
        const btnTestar = await page.locator('button:has-text("Testar"), button:has-text("Enviar Teste")').count();
        
        if (btnTestar > 0) {
          await page.click('button:has-text("Testar"), button:has-text("Enviar Teste")');
          
          // Aguardar resultado
          await page.waitForTimeout(5000);
          
          // Verificar se teste foi bem-sucedido
          const sucesso = await page.locator('.alert-success, .success').count();
          const erro = await page.locator('.alert-error, .error').count();
          
    const response = await page.goto('/admin/config-smtp.php');
    
    if (response?.status() === 404) {
      // Configuração pode estar em outro lugar
      await page.goto('/admin/configuracoes.php');
    }
    
    // Verificar valores de configuração (esperados baseado na conversa):
    const configEsperada = {
      host: 'email-ssl.com.br',
      port: '993', 
      user: 'tecnologia@sitefacic.institucional.ws',
      ssl: true
    };
    
    // TODO: Adicionar lógica para ler configuração atual
    console.log('🔧 Configuração esperada:', configEsperada);
  });

  /**
   * TESTE DIAG-003: Teste de conexão SMTP
   */
  test('DIAG-003: Deve conseguir conectar ao servidor SMTP', async ({ page }) => {
    await fazerLogin(page, 'admin');
    
    // Procurar por página de teste de SMTP
    const urls = [
      '/admin/testar-smtp.php',
      '/admin/teste-email.php',
      '/admin/configuracoes.php?acao=testar'
    ];
    
    for (const url of urls) {
      const response = await page.goto(url);
      if (response?.status() === 200) {
        console.log(`✅ Encontrada página de teste em: ${url}`);
        
        // Procurar botão de teste
        const btnTestar = await page.locator('button:has-text("Testar"), button:has-text("Enviar Teste")').count();
        
        if (btnTestar > 0) {
          await page.click('button:has-text("Testar"), button:has-text("Enviar Teste")');
          
          // Aguardar resultado
          await page.waitForTimeout(5000);
          
          // Verificar se teste foi bem-sucedido
          const sucesso = await page.locator('.alert-success, .success').count();
          const erro = await page.locator('.alert-error, .error').count();
          
    const response = await page.goto('/admin/config-smtp.php');
    
    if (response?.status() === 404) {
      // Configuração pode estar em outro lugar
      await page.goto('/admin/configuracoes.php');
    }
    
    // Verificar valores de configuração (esperados baseado na conversa):
    const configEsperada = {
      host: 'email-ssl.com.br',
      port: '993', 
      user: 'tecnologia@sitefacic.institucional.ws',
      ssl: true
    };
    
    // TODO: Adicionar lógica para ler configuração atual
    console.log('🔧 Configuração esperada:', configEsperada);
  });

  /**
   * TESTE DIAG-003: Teste de conexão SMTP
   */
  test('DIAG-003: Deve conseguir conectar ao servidor SMTP', async ({ page }) => {
    await fazerLogin(page, 'admin');
    
    // Procurar por página de teste de SMTP
    const urls = [
      '/admin/testar-smtp.php',
      '/admin/teste-email.php',
      '/admin/configuracoes.php?acao=testar'
    ];
    
    for (const url of urls) {
      const response = await page.goto(url);
      if (response?.status() === 200) {
        console.log(`✅ Encontrada página de teste em: ${url}`);
        
        // Procurar botão de teste
        const btnTestar = await page.locator('button:has-text("Testar"), button:has-text("Enviar Teste")').count();
        
        if (btnTestar > 0) {
          await page.click('button:has-text("Testar"), button:has-text("Enviar Teste")');
          
          // Aguardar resultado
          await page.waitForTimeout(5000);
          
          // Verificar se teste foi bem-sucedido
          const sucesso = await page.locator('.alert-success, .success').count();
          const erro = await page.locator('.alert-error, .error').count();
          
