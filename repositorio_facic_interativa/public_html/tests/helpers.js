/**
 * Helpers e Utilitários para Testes do FACIC Interativa
 * Funções compartilhadas entre os testes
 */

/**
 * Credenciais de teste para diferentes tipos de usuário
 */
export const USUARIOS_TESTE = {
  aluno: {
    login: 'aluno.teste@facic.com.br',
    senha: 'Teste123!',
    nome: 'Aluno Teste',
    ra: '2024001'
  },
  professor: {
    login: 'professor.teste@facic.com.br',
    senha: 'Prof123!',
    nome: 'Professor Teste'
  },
  admin: {
    login: 'admin@facic.com.br',
    senha: 'Admin123!',
    nome: 'Administrador'
  }
};

/**
 * URLs do sistema
 */
export const URLS = {
  login: '/index.php',
  dashboard: '/dashboard.php',
  enviarAtividade: '/enviarAtividade.php',
  cadastroAluno: '/gravarUsuario.php',
  cadastroProva: '/cadastrarProva.php',
  visualizarProvas: '/visualizarProvas2.php',
  aplicarProva: '/aplicarProva.php'
};

/**
 * Realiza login no sistema
 * @param {Page} page - Página do Playwright
 * @param {string} tipo - Tipo de usuário: 'aluno', 'professor', 'admin'
 */
export async function fazerLogin(page, tipo = 'aluno') {
  const usuario = USUARIOS_TESTE[tipo];
  
  await page.goto(URLS.login);
  await page.fill('input[name="login"]', usuario.login);
  await page.fill('input[name="senha"]', usuario.senha);
  await page.click('button[type="submit"]');
  
  // Aguardar redirecionamento
  await page.waitForURL('**/dashboard.php', { timeout: 10000 });
}

/**
 * Verifica se um email foi disparado
 * @param {Page} page - Página do Playwright
 * @param {string} destinatario - Email do destinatário
 * @param {string} assunto - Assunto do email
 */
export async function verificarEmailDisparado(page, destinatario, assunto) {
  // Implementar verificação de logs ou mock de email
  // Por enquanto, apenas uma estrutura
  console.log(`Verificando email para: ${destinatario} | Assunto: ${assunto}`);
}

/**
 * Limpa dados de teste do banco
 */
export async function limparDadosTeste() {
  // Implementar limpeza de dados de teste
  console.log('Limpando dados de teste...');
}

/**
 * Cria um aluno de teste
 */
export async function criarAlunoTeste(page) {
  await page.goto('/gravarUsuario.php');
  // Implementar criação de aluno
}

/**
 * Espera por elemento com timeout customizado
 */
export async function esperarElemento(page, seletor, timeout = 5000) {
  await page.waitForSelector(seletor, { timeout });
}

/**
 * Preenche formulário de atividade
 */
export async function preencherFormularioAtividade(page, dados) {
  await page.fill('textarea[name="descricao"]', dados.descricao || 'Atividade teste');
  
  if (dados.arquivo) {
    await page.setInputFiles('input[type="file"]', dados.arquivo);
  }
}

/**
 * Verifica mensagem de sucesso na tela
 */
export async function verificarMensagemSucesso(page, texto) {
  const mensagem = await page.locator('.alert-success, .success-message').textContent();
  return mensagem.includes(texto);
}

/**
 * Verifica mensagem de erro na tela
 */
export async function verificarMensagemErro(page, texto) {
  const mensagem = await page.locator('.alert-error, .error-message').textContent();
  return mensagem.includes(texto);
}
