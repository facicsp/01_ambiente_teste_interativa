const { test, expect } = require('@playwright/test');
const { fazerLogin } = require('../helpers');

/**
 * TESTES DE EMAILS PARA ADMINISTRADORES
 * 
 * Baseado no mapeamento do sistema, estes testes cobrem:
 * 1. Notificações de sistema
 * 2. Relatórios
 * 3. Alertas críticos
 */

test.describe('Emails para Admin - Gestão de Usuários', () => {
  
  test.beforeEach(async ({ page }) => {
    await fazerLogin(page, 'admin');
  });

  /**
   * TESTE: Confirmação de criação de usuário
   */
  test('CT-EMAIL-ADM-001: Deve confirmar criação de novo usuário ao admin', async ({ page }) => {
    await page.goto('/gravarUsuario.php');
    
    const novoUsuario = {
      nome: 'Teste Admin',
      email: `teste${Date.now()}@facic.com`,
      tipo: 'professor'
    };
    
    await page.fill('input[name="nome"]', novoUsuario.nome);
    await page.fill('input[name="email"]', novoUsuario.email);
    await page.selectOption('select[name="tipo"]', novoUsuario.tipo);
    await page.click('button[type="submit"]');
    
    await expect(page.locator('.alert-success')).toBeVisible();
    
    // Admin deve receber confirmação do cadastro
  });

  /**
   * TESTE: Notificação de exclusão de usuário
   */
  test('CT-EMAIL-ADM-002: Deve notificar admin ao excluir usuário', async ({ page }) => {
    await page.goto('/usuarios.php');
    
    // Clicar em excluir um usuário
    await page.click('.btn-excluir[data-id="test-user"]');
    await page.click('button:has-text("Confirmar")');
    
    // Admin deve receber email de confirmação da exclusão
  });
});

test.describe('Emails para Admin - Monitoramento do Sistema', () => {
  
  /**
   * TESTE: Relatório de emails enviados
   */
  test('CT-EMAIL-ADM-010: Deve gerar relatório diário de emails enviados', async ({ page }) => {
    await fazerLogin(page, 'admin');
    await page.goto('/relatorios/emails.php');
    
    // Verificar se há relatório do dia
    const relatorio = await page.locator('.relatorio-diario').count();
    expect(relatorio).toBeGreaterThan(0);
  });

  /**
   * TESTE: Alerta de falha de email
   */
  test('CT-EMAIL-ADM-011: Deve alertar admin quando email falhar', async ({ page }) => {
    // Admin deve receber alerta quando:
    // - Servidor SMTP não responder
    // - Credenciais inválidas
    // - Quota excedida
    // - Email bounce
  });

  /**
   * TESTE: Resumo de atividades do sistema
   */
  test('CT-EMAIL-ADM-012: Deve enviar resumo semanal ao admin', async ({ page }) => {
    // Email semanal com:
    // - Total de usuários ativos
    // - Total de atividades enviadas
    // - Total de provas aplicadas
    // - Taxa de emails entregues
  });
});

test.describe('Emails para Admin - Diagnóstico e Testes', () => {
  
  /**
   * TESTE: Interface de teste de SMTP
   */
  test('CT-EMAIL-ADM-020: Admin deve ter interface para testar SMTP', async ({ page }) => {
    await fazerLogin(page, 'admin');
    
    // Deve existir página de configurações
    await page.goto('/admin/configuracoes-email.php');
    
    const status = await page.locator('.config-smtp').count();
    expect(status).toBeGreaterThan(0);
  });

  /**
   * TESTE: Log de emails
   */
  test('CT-EMAIL-ADM-021: Admin deve visualizar logs de emails', async ({ page }) => {
    await fazerLogin(page, 'admin');
    await page.goto('/admin/logs-email.php');
    
    // Tabela de logs deve existir
    await expect(page.locator('table.logs-email')).toBeVisible();
    
    // Deve mostrar:
    // - Data/hora
    // - Destinatário
    // - Assunto
    // - Status (enviado/falhou)
    // - Erro (se houver)
  });

  /**
   * TESTE: Reenvio de emails falhados
   */
  test('CT-EMAIL-ADM-022: Admin deve poder reenviar emails que falharam', async ({ page }) => {
    await fazerLogin(page, 'admin');
    await page.goto('/admin/logs-email.php');
    
    // Filtrar emails com falha
    await page.selectOption('select[name="status"]', 'falhou');
    await page.click('button:has-text("Filtrar")');
    
    // Deve haver botão de reenvio
    const btnReenviar = await page.locator('button:has-text("Reenviar")').count();
    expect(btnReenviar).toBeGreaterThan(0);
  });

  /**
   * TESTE: Configuração de email do sistema
   */
  test('CT-EMAIL-ADM-023: Admin deve visualizar configurações atuais', async ({ page }) => {
    await fazerLogin(page, 'admin');
    await page.goto('/admin/configuracoes-email.php');
    
    // Verificar campos de configuração
    await expect(page.locator('input[name="smtp_host"]')).toBeVisible();
    await expect(page.locator('input[name="smtp_port"]')).toBeVisible();
    await expect(page.locator('input[name="smtp_user"]')).toBeVisible();
    
    // Valores devem estar preenchidos
    const smtpHost = await page.locator('input[name="smtp_host"]').inputValue();
    expect(smtpHost).not.toBe('');
  });

  /**
   * TESTE CRÍTICO: Verificar configuração SMTP atual
   */
  test('CT-EMAIL-ADM-024: Configuração SMTP deve estar correta', async ({ page }) => {
    await fazerLogin(page, 'admin');
    await page.goto('/admin/configuracoes-email.php');
    
    // Valores esperados (baseado na conversa anterior):
    const configEsperada = {
      host: 'email-ssl.com.br',
      port: '993',
      user: 'tecnologia@sitefacic.institucional.ws'
    };
    
    const smtpHost = await page.locator('input[name="smtp_host"]').inputValue();
    const smtpPort = await page.locator('input[name="smtp_port"]').inputValue();
    const smtpUser = await page.locator('input[name="smtp_user"]').inputValue();
    
    expect(smtpHost).toBe(configEsperada.host);
    expect(smtpPort).toBe(configEsperada.port);
    expect(smtpUser).toBe(configEsperada.user);
  });
});

test.describe('Emails para Admin - Análise de Problemas Reportados', () => {
  
  /**
   * TESTE DIAGNÓSTICO: Verificar tabela de emails enviados
   */
  test('CT-DIAG-ADM-001: Tabela de emails deve ter registros', async ({ page }) => {
    await fazerLogin(page, 'admin');
    
    // TODO: Query para verificar tabela emails_enviados
    // SELECT COUNT(*) FROM emails_enviados WHERE DATE(data_envio) = CURDATE()
    
    // Se não houver registros mesmo com atividades sendo enviadas,
    // significa que enviarEmail.php não está gravando no banco
  });

  /**
   * TESTE DIAGNÓSTICO: Verificar último envio de email
   */
  test('CT-DIAG-ADM-002: Deve mostrar último email enviado', async ({ page }) => {
    await fazerLogin(page, 'admin');
    await page.goto('/admin/logs-email.php');
    
    // Ordenar por mais recente
    await page.selectOption('select[name="ordenar"]', 'data_desc');
    
    // Verificar se há registro recente
    const primeiroEmail = await page.locator('table tr:first-child td').first();
    await expect(primeiroEmail).toBeVisible();
  });

  /**
   * TESTE DIAGNÓSTICO: Taxa de falha de emails
   */
  test('CT-DIAG-ADM-003: Taxa de falha não deve exceder 5%', async ({ page }) => {
    await fazerLogin(page, 'admin');
    await page.goto('/admin/dashboard-emails.php');
    
    // Verificar métricas
    const taxaFalha = await page.locator('.metrica-falha').textContent();
    const taxa = parseFloat(taxaFalha || '0');
    
    expect(taxa).toBeLessThan(5);
    
    // Se taxa > 5%, investigar:
    // - Problemas com servidor SMTP
    // - Emails inválidos no cadastro
    // - Problemas de rede/firewall
  });

  /**
   * TESTE DIAGNÓSTICO: Emails em fila
   */
  test('CT-DIAG-ADM-004: Não deve haver emails presos em fila', async ({ page }) => {
    await fazerLogin(page, 'admin');
    
    // TODO: Verificar se há sistema de fila
    // Se houver, verificar quantos emails estão aguardando
    
    // Emails não devem ficar mais de 5 minutos em fila
  });
});
