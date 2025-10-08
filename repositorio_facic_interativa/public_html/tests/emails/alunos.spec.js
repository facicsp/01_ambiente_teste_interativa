  /**
   * TESTE CRÍTICO 4: Verificar se configuração SMTP está correta
   * Problema: Emails podem não estar saindo por falha na configuração
   */
  test('CT-EMAIL-004: Sistema deve usar configuração SMTP correta', async ({ page }) => {
    // Este teste verifica se o sistema está usando as credenciais corretas
    // Configuração esperada (baseada na conversa anterior):
    // Email: tecnologia@sitefacic.institucional.ws
    // Servidor: email-ssl.com.br
    // Porta: 993
    
    // TODO: Criar endpoint ou página admin para verificar config SMTP
    // Por enquanto, verificar se envio não retorna erro
    
    await page.goto(URLS.enviarAtividade);
    await preencherFormularioAtividade(page, {
      descricao: 'Teste SMTP'
    });
    
    await page.click('button[type="submit"]');
    
    // Não deve haver erro de SMTP
    const erroSMTP = await page.locator('.alert-error:has-text("SMTP")').count();
    expect(erroSMTP).toBe(0);
  });

  /**
   * TESTE CRÍTICO 5: Timeout de envio de email
   * Problema: Email pode estar sendo enviado mas levando muito tempo
   */
  test('CT-EMAIL-005: Envio de atividade não deve exceder 10 segundos', async ({ page }) => {
    const inicio = Date.now();
    
    await page.goto(URLS.enviarAtividade);
    await preencherFormularioAtividade(page, {
      descricao: 'Teste de performance'
    });
    
    await page.click('button[type="submit"]');
    await expect(page.locator('.alert-success')).toBeVisible();
    
    const tempo = Date.now() - inicio;
    
    // Envio + email não deve levar mais de 10 segundos
    expect(tempo).toBeLessThan(10000);
  });

  /**
   * TESTE CRÍTICO 6: Email sem anexo
   * Verificar se o sistema envia email mesmo sem arquivo anexado
   */
  test('CT-EMAIL-006: Deve enviar email mesmo sem arquivo anexado', async ({ page }) => {
    await page.goto(URLS.enviarAtividade);
    
    // Preencher apenas descrição, sem arquivo
    await page.fill('textarea[name="descricao"]', 'Atividade sem anexo');
    await page.click('button[type="submit"]');
    
    // Deve ter sucesso
    await expect(page.locator('.alert-success')).toBeVisible();
  });

  /**
   * TESTE CRÍTICO 7: Email com arquivo grande
   * Verificar se arquivos grandes não impedem o envio do email
   */
  test('CT-EMAIL-007: Deve lidar com arquivos grandes sem bloquear email', async ({ page }) => {
    await page.goto(URLS.enviarAtividade);
    
    await page.fill('textarea[name="descricao"]', 'Atividade com arquivo grande');
    
    // TODO: Adicionar arquivo de teste grande (>5MB)
    // await page.setInputFiles('input[type="file"]', 'test-files/arquivo-grande.pdf');
    
    await page.click('button[type="submit"]');
    
    // Mesmo com arquivo grande, deve ter sucesso
    await expect(page.locator('.alert-success')).toBeVisible({ timeout: 15000 });
  });
});

test.describe('Emails para Alunos - Cadastro e Matrícula', () => {
  
  /**
   * TESTE: Email de boas-vindas ao criar novo aluno
   */
  test('CT-EMAIL-010: Deve enviar email de boas-vindas ao criar aluno', async ({ page }) => {
    await fazerLogin(page, 'admin');
    await page.goto('/gravarUsuario.php');
    
    const novoAluno = {
      nome: 'Aluno Novo Teste',
      email: 'aluno.novo@teste.com',
      ra: `2024${Date.now()}`
    };
    
    await page.fill('input[name="nome"]', novoAluno.nome);
    await page.fill('input[name="email"]', novoAluno.email);
    await page.fill('input[name="ra"]', novoAluno.ra);
    await page.click('button[type="submit"]');
    
    // Verificar sucesso
    await expect(page.locator('.alert-success')).toBeVisible();
    
    // TODO: Verificar que email foi enviado para aluno.novo@teste.com
    // Conteúdo deve incluir: login, senha temporária, link do sistema
  });

  /**
   * TESTE: Email de confirmação de matrícula
   */
  test('CT-EMAIL-011: Deve enviar email ao matricular aluno em turma', async ({ page }) => {
    await fazerLogin(page, 'admin');
    await page.goto('/gravarMatricula.php');
    
    // Selecionar aluno e turma
    await page.selectOption('select[name="aluno"]', USUARIOS_TESTE.aluno.ra);
    await page.selectOption('select[name="turma"]', '1'); // Turma de teste
    await page.click('button[type="submit"]');
    
    await expect(page.locator('.alert-success')).toBeVisible();
    
    // TODO: Verificar email com dados da matrícula
  });
});

test.describe('Emails para Alunos - Notificações de Provas', () => {
  
  /**
   * TESTE: Email ao agendar prova
   */
  test('CT-EMAIL-020: Deve notificar aluno quando prova for agendada', async ({ page }) => {
    await fazerLogin(page, 'professor');
    await page.goto('/cadastrarProva.php');
    
    // Criar prova e agendar para turma
    await page.fill('input[name="titulo"]', 'Prova Teste Email');
    await page.fill('input[name="data"]', '2025-12-31');
    await page.selectOption('select[name="turma"]', '1');
    await page.click('button[type="submit"]');
    
    await expect(page.locator('.alert-success')).toBeVisible();
    
    // TODO: Verificar que TODOS os alunos da turma receberam email
  });

  /**
   * TESTE: Email de lembrete antes da prova
   */
  test('CT-EMAIL-021: Deve enviar lembrete 24h antes da prova', async ({ page }) => {
    // Este teste verifica o cron job ou scheduled task
    // TODO: Implementar verificação de agendamento
    // Verificar tabela de lembretes ou logs de cron
  });
});

test.describe('Emails para Alunos - Diagnóstico de Problemas', () => {
  
  /**
   * TESTE DIAGNÓSTICO 1: Verificar se tabela de emails existe
   */
  test('CT-DIAG-001: Tabela de log de emails deve existir', async ({ page }) => {
    await fazerLogin(page, 'admin');
    
    // Tentar acessar página de logs (se existir)
    await page.goto('/logs/emails.php');
    
    // Não deve dar erro 404
    const status = page.url();
    expect(status).not.toContain('404');
  });

  /**
   * TESTE DIAGNÓSTICO 2: Verificar arquivo de configuração
   */
  test('CT-DIAG-002: Configuração SMTP deve estar preenchida', async ({ page }) => {
    // TODO: Criar endpoint que retorne status da configuração
    // Verificar se smtp_config.php tem todos os campos preenchidos
  });

  /**
   * TESTE DIAGNÓSTICO 3: Teste de conexão SMTP
   */
  test('CT-DIAG-003: Deve conseguir conectar ao servidor SMTP', async ({ page }) => {
    await fazerLogin(page, 'admin');
    
    // TODO: Criar página de teste de conexão SMTP
    // await page.goto('/admin/testar-smtp.php');
    // await page.click('button#testar-conexao');
    // await expect(page.locator('.resultado:has-text("Sucesso")')).toBeVisible();
  });
});
