# Guia de Instalação e Configuração do Sentry via MCP - FACIC Interativa

## 📋 Índice
1. [Status Atual](#status-atual)
2. [Problema Identificado](#problema-identificado)
3. [Solução Passo a Passo](#solução-passo-a-passo)
4. [Configuração do DSN Correto](#configuração-do-dsn-correto)
5. [Integração nos Arquivos PHP](#integração-nos-arquivos-php)
6. [Testes de Validação](#testes-de-validação)
7. [Monitoramento no Painel Sentry](#monitoramento-no-painel-sentry)

---

## 🔍 Status Atual

### ✅ O que já está instalado:
- **Sentry PHP SDK v4.15.2** (via Composer)
- Arquivo de configuração: `sentry_config.php`
- Arquivo JS: `sentry-config.js`
- Todas as dependências necessárias

### ❌ Problema Identificado:
**O arquivo `sentry_config.php` NÃO está sendo incluído em nenhum arquivo do sistema**, por isso o Sentry não está capturando erros no painel.

### 🎯 Projeto Sentry Identificado:
- **Organização:** facic
- **Projeto:** interativa-facic
- **DSN:** Já configurado no arquivo `sentry_config.php`
- **URL do Projeto:** https://facic.sentry.io

---

## 🛠️ Solução Passo a Passo

### Passo 1: Atualizar o arquivo `conexao.php`

O arquivo `conexao.php` é carregado em praticamente todos os arquivos do sistema. Vamos adicionar o Sentry lá:

```php
<?php
  date_default_timezone_set('America/Sao_Paulo');
  
  // ============================================
  // INICIALIZAÇÃO DO SENTRY - FACIC INTERATIVA
  // ============================================
  // Incluir configuração do Sentry ANTES de qualquer outra coisa
  if (file_exists(__DIR__ . '/sentry_config.php')) {
      require_once __DIR__ . '/sentry_config.php';
  }
  
  if (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    echo "<script>(function() { document.title = 'FACIC INTERATIVA' })()</script>";
  
  class Seguranca{ 
        function antisql($sql)
    {
      // remove palavras que contenham sintaxe sql
      $sql = trim($sql);//limpa espaços vazio
      $sql = addslashes($sql);//Adiciona barras invertidas a uma string
      return $sql;
    }
  }
    
  $conexao = mysql_connect("interativa.vpshost0360.mysql.dbaas.com.br","interativa","Q!w2E#r4110452") or die("Não foi possivel conectar!");
  mysql_select_db("interativa",$conexao) or die("Banco inexistente!");
    
  // BLOQUEIA ALTERACOES DE COORDENADORES EM PERFIL DE PROFESSORES
  if (isset($_SESSION["coordenando"]) && $_SESSION["coordenando"] == true) {
    $__arquivo = basename($_SERVER['SCRIPT_FILENAME']);
    if (strpos($__arquivo, "operacao") !== false 
        || strpos($__arquivo, "alterar") !== false 
        || strpos($__arquivo, "gravar") !== false) {
          echo "<script>alert('Ops! Você não tem acesso a essa funcionalidade.'); "
            . "window.location='index.php';</script>";
          exit;
        }
  }
?>
```

### Passo 2: Atualizar o DSN no `sentry_config.php`

Vamos garantir que o DSN está correto. O arquivo `sentry_config.php` já existe, mas vamos verificar se o DSN está correto para o projeto **interativa-facic**:

**IMPORTANTE:** O DSN atual no arquivo parece estar correto, mas vamos criar um arquivo de teste para validar.

### Passo 3: Criar arquivo de teste

Crie um arquivo chamado `teste_sentry.php` na raiz do projeto:

```php
<?php
// Arquivo: teste_sentry.php
// Teste de integração do Sentry no sistema FACIC Interativa

require_once __DIR__ . '/sentry_config.php';

echo "<h1>Teste de Integração Sentry - FACIC Interativa</h1>";
echo "<hr>";

// Teste 1: Verificar se o Sentry está inicializado
echo "<h2>1. Status da Inicialização</h2>";
try {
    \Sentry\captureMessage('Teste de mensagem Sentry - FACIC Interativa', \Sentry\Severity::info());
    echo "<p style='color: green;'>✅ Mensagem de teste enviada com sucesso!</p>";
    echo "<p>Verifique no painel do Sentry: <a href='https://facic.sentry.io/projects/interativa-facic/' target='_blank'>https://facic.sentry.io/projects/interativa-facic/</a></p>";
} catch (\Exception $e) {
    echo "<p style='color: red;'>❌ Erro ao enviar mensagem: " . $e->getMessage() . "</p>";
}

// Teste 2: Capturar uma exceção de teste
echo "<h2>2. Teste de Captura de Exceção</h2>";
try {
    throw new \Exception('Exceção de teste do sistema FACIC Interativa');
} catch (\Exception $e) {
    \Sentry\captureException($e);
    echo "<p style='color: green;'>✅ Exceção de teste capturada!</p>";
    echo "<p>Exceção: " . $e->getMessage() . "</p>";
}

// Teste 3: Testar função customizada de erro crítico acadêmico
echo "<h2>3. Teste de Erro Crítico Acadêmico</h2>";
try {
    throw new \Exception('Erro crítico acadêmico de teste - Sistema FACIC');
} catch (\Exception $e) {
    capturarErroCriticoAcademico($e, [
        'modulo' => 'teste',
        'tipo' => 'teste_integracao',
        'usuario' => 'admin_teste'
    ]);
    echo "<p style='color: green;'>✅ Erro crítico acadêmico capturado com contexto!</p>";
}

// Teste 4: Informações de configuração
echo "<h2>4. Informações de Configuração</h2>";
echo "<ul>";
echo "<li><strong>Organização:</strong> facic</li>";
echo "<li><strong>Projeto:</strong> interativa-facic</li>";
echo "<li><strong>Ambiente:</strong> production</li>";
echo "<li><strong>Release:</strong> interativa-facic-v1.0.0</li>";
echo "</ul>";

echo "<hr>";
echo "<h2>5. Próximos Passos</h2>";
echo "<ol>";
echo "<li>Aguarde cerca de 30 segundos</li>";
echo "<li>Acesse o painel do Sentry: <a href='https://facic.sentry.io/projects/interativa-facic/' target='_blank'>https://facic.sentry.io/projects/interativa-facic/</a></li>";
echo "<li>Verifique se os eventos de teste aparecem no painel</li>";
echo "<li>Se os eventos aparecerem, a integração está funcionando corretamente!</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Data/Hora do teste:</strong> " . date('Y-m-d H:i:s') . "</p>";
?>
```

---

## 📝 Configuração do DSN Correto

### Verificar/Obter o DSN do Projeto

Para obter o DSN correto do projeto **interativa-facic**:

1. Acesse: https://facic.sentry.io/settings/projects/interativa-facic/keys/
2. Copie o DSN que aparece na página
3. O DSN tem este formato: `https://CHAVE_PUBLICA@o4510030147026944.ingest.us.sentry.io/ID_DO_PROJETO`

**DSN Atual no código:**
```
https://6804d2e8571595c2ccd363f2fe154868@o4510030147026944.ingest.us.sentry.io/4510030257061888
```

### Atualizar o DSN se necessário

Se o DSN estiver incorreto, edite o arquivo `sentry_config.php` na linha 17:

```php
\Sentry\init([
    'dsn' => 'SEU_DSN_CORRETO_AQUI',
    // ... resto das configurações
]);
```

---

## 🔗 Integração nos Arquivos PHP

### Arquivos que já devem incluir o Sentry após a modificação do `conexao.php`:

Praticamente todos os arquivos PHP do sistema já incluem `conexao.php`, então a integração será automática. Exemplos:

- ✅ `index.php`
- ✅ `login.php`
- ✅ `cadastroUsuario.php`
- ✅ `cadastroProva2.php`
- ✅ Todos os arquivos de API (`api/*.php`)
- ✅ Todos os arquivos de operação (`operacao*.php`)

### Para arquivos que NÃO incluem `conexao.php`:

Se houver algum arquivo que não inclui `conexao.php`, adicione manualmente:

```php
<?php
require_once __DIR__ . '/sentry_config.php';
// ... resto do código
?>
```

---

## ✅ Testes de Validação

### Teste 1: Executar o arquivo de teste

1. Crie o arquivo `teste_sentry.php` com o conteúdo fornecido acima
2. Acesse: `http://seu-dominio/teste_sentry.php`
3. Verifique se todas as mensagens de sucesso (✅) aparecem
4. Aguarde 30 segundos

### Teste 2: Verificar no painel do Sentry

1. Acesse: https://facic.sentry.io/projects/interativa-facic/
2. Clique em "Issues" no menu lateral
3. Você deve ver os eventos de teste:
   - "Teste de mensagem Sentry - FACIC Interativa"
   - "Exceção de teste do sistema FACIC Interativa"
   - "Erro crítico acadêmico de teste - Sistema FACIC"

### Teste 3: Gerar um erro real

Crie um arquivo `teste_erro_real.php`:

```php
<?php
require_once __DIR__ . '/conexao.php';

// Isto vai gerar um erro de divisão por zero
$resultado = 10 / 0;

echo "Se você está vendo isso, algo deu errado!";
?>
```

1. Acesse: `http://seu-dominio/teste_erro_real.php`
2. Verifique se o erro aparece no Sentry em alguns segundos

---

## 📊 Monitoramento no Painel Sentry

### Acessar o Dashboard

URL: https://facic.sentry.io/projects/interativa-facic/

### O que monitorar:

1. **Issues (Problemas):**
   - Erros críticos do sistema
   - Exceções não tratadas
   - Problemas de SQL
   - Falhas de autenticação

2. **Performance:**
   - Queries lentas (>2 segundos)
   - Tempo de carregamento de páginas
   - Gargalos de performance

3. **Releases:**
   - Versão atual: `interativa-facic-v1.0.0`
   - Erros por versão
   - Deploy tracking

### Tags importantes configuradas:

- `sistema`: interativa-facic
- `instituicao`: facic
- `tipo`: sistema-academico
- `plataforma`: php-mysql-legacy
- `ambiente`: production

### Alertas configuráveis:

Você pode configurar alertas no Sentry para:
- Erros críticos (notificação imediata)
- Aumento súbito de erros
- Problemas de performance
- Queries lentas

---

## 🚀 Exemplos de Uso no Código

### Capturar erro em operações críticas:

```php
<?php
require_once __DIR__ . '/conexao.php';

try {
    // Operação crítica (ex: salvar nota de aluno)
    $sql = "INSERT INTO notas ...";
    $result = mysql_query($sql);
    
    if (!$result) {
        throw new Exception("Erro ao salvar nota: " . mysql_error());
    }
    
} catch (Exception $e) {
    // Capturar erro acadêmico crítico
    capturarErroCriticoAcademico($e, [
        'modulo' => 'notas',
        'operacao' => 'salvar_nota',
        'sql' => $sql
    ]);
    
    echo "Erro ao processar. Nosso time foi notificado.";
}
?>
```

### Monitorar queries lentas:

```php
<?php
require_once __DIR__ . '/conexao.php';

$inicio = microtime(true);

$sql = "SELECT * FROM alunos WHERE ...";
$result = mysql_query($sql);

$tempo = microtime(true) - $inicio;

// Capturar queries que demoram mais de 2 segundos
if ($tempo > 2.0) {
    capturarQueryLentaFACIC($sql, $tempo, [
        'tabela' => 'alunos',
        'operacao' => 'consulta'
    ]);
}
?>
```

---

## 📞 Suporte

### Documentação Oficial:
- Sentry PHP: https://docs.sentry.io/platforms/php/
- Configuração: https://docs.sentry.io/platforms/php/configuration/

### Em caso de problemas:

1. Verifique os logs do PHP: `error_log`
2. Verifique se o DSN está correto
3. Teste a conectividade com: `teste_sentry.php`
4. Verifique se o `vendor/autoload.php` existe

---

## ✨ Resumo da Instalação

| Etapa | Status | Ação Necessária |
|-------|--------|-----------------|
| 1. SDK Instalado | ✅ Sim | Nenhuma |
| 2. Arquivo de Config | ✅ Criado | Verificar DSN |
| 3. Integração no Sistema | ❌ Pendente | **Modificar conexao.php** |
| 4. Teste de Validação | ❌ Pendente | **Executar teste_sentry.php** |
| 5. Verificação no Painel | ❌ Pendente | **Acessar Sentry Dashboard** |

---

**Data de criação:** 03/10/2025  
**Versão do documento:** 1.0  
**Projeto:** FACIC Interativa  
**Responsável:** Sistema de Monitoramento via MCP
