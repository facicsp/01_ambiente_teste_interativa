// @ts-check
const { defineConfig, devices } = require('@playwright/test');

/**
 * Configuração do Playwright para testes do FACIC Interativa
 * @see https://playwright.dev/docs/test-configuration
 */
module.exports = defineConfig({
  testDir: './tests',
  
  /* Tempo máximo de execução por teste */
  timeout: 30 * 1000,
  
  /* Configuração de expect */
  expect: {
    timeout: 5000
  },
  
  /* Executar testes em paralelo */
  fullyParallel: true,
  
  /* Falhar build no CI se houver testes marcados como only */
  forbidOnly: !!process.env.CI,
  
  /* Número de tentativas em caso de falha no CI */
  retries: process.env.CI ? 2 : 0,
  
  /* Workers para execução paralela */
  workers: process.env.CI ? 1 : undefined,
  
  /* Configuração de reporter */
  reporter: [
    ['html', { outputFolder: 'playwright-report' }],
    ['json', { outputFile: 'test-results.json' }],
    ['list']
  ],
  
  /* Configuração compartilhada para todos os projetos */
  use: {
    /* URL base para testes */
    baseURL: 'http://localhost/facic_interativa',
    
    /* Coletar trace em caso de falha */
    trace: 'on-first-retry',
    
    /* Screenshot em caso de falha */
    screenshot: 'only-on-failure',
    
    /* Video em caso de falha */
    video: 'retain-on-failure',
    
    /* Timeout para ações */
    actionTimeout: 10000,
    
    /* Timeout para navegação */
    navigationTimeout: 15000,
  },

  /* Configuração de projetos para diferentes browsers */
  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },

    {
      name: 'firefox',
      use: { ...devices['Desktop Firefox'] },
    },

    {
      name: 'webkit',
      use: { ...devices['Desktop Safari'] },
    },

    /* Testes em dispositivos móveis */
    {
      name: 'Mobile Chrome',
      use: { ...devices['Pixel 5'] },
    },
    {
      name: 'Mobile Safari',
      use: { ...devices['iPhone 12'] },
    },
  ],

  /* Servidor de desenvolvimento */
  // webServer: {
  //   command: 'npm run start',
  //   url: 'http://localhost:3000',
  //   reuseExistingServer: !process.env.CI,
  // },
});
