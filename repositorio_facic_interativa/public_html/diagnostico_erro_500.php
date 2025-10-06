<?php
/**
 * Diagnóstico de Erro 500 - FACIC Interativa + Sentry
 * 
 * Este arquivo ajuda a identificar a causa do erro 500
 */

// Ativar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnóstico do Sistema - FACIC Interativa</h1>";
echo "<hr>";

// Teste 1: Verificar arquivos necessários
echo "<h2>1. Verificação de Arquivos</h2>";

$arquivos = [
    'vendor/autoload.php',
    'sentry_config.php',
    'conexao.php'
];

foreach ($arquivos as $arquivo) {
    $caminho = __DIR__ . '/' . $arquivo;
    if (file_exists($caminho)) {
        echo "✅ <strong>$arquivo</strong> - Existe<br>";
    } else {
        echo "❌ <strong>$arquivo</strong> - NÃO EXISTE<br>";
    }
}

echo "<hr>";

// Teste 2: Verificar versão do PHP
echo "<h2>2. Versão do PHP</h2>";
echo "Versão: <strong>" . PHP_VERSION . "</strong><br>";

if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    echo "✅ Versão compatível com Sentry<br>";
} else {
    echo "❌ Versão muito antiga. Sentry requer PHP 7.2+<br>";
}

echo "<hr>";

// Teste 3: Verificar extensões PHP
echo "<h2>3. Extensões PHP Necessárias</h2>";

$extensoes = ['curl', 'json', 'mbstring'];

foreach ($extensoes as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ <strong>$ext</strong> - Instalada<br>";
    } else {
        echo "❌ <strong>$ext</strong> - NÃO INSTALADA (Necessária para Sentry)<br>";
    }
}

echo "<hr>";

// Teste 4: Tentar carregar o autoload
echo "<h2>4. Teste de Carregamento do Autoload</h2>";

try {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        echo "✅ Autoload carregado com sucesso<br>";
        
        // Verificar se classes do Sentry existem
        if (class_exists('\Sentry\SentrySdk')) {
            echo "✅ Classe Sentry\SentrySdk encontrada<br>";
        } else {
            echo "❌ Classe Sentry\SentrySdk NÃO encontrada<br>";
        }
    } else {
        echo "❌ vendor/autoload.php não existe<br>";
        echo "<strong>SOLUÇÃO:</strong> Execute: <code>composer install</code><br>";
    }
} catch (\Exception $e) {
    echo "❌ Erro ao carregar autoload: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Teste 5: Tentar carregar sentry_config.php
echo "<h2>5. Teste de Carregamento do sentry_config.php</h2>";

try {
    if (file_exists(__DIR__ . '/sentry_config.php')) {
        // Capturar output
        ob_start();
        require_once __DIR__ . '/sentry_config.php';
        $output = ob_get_clean();
        
        echo "✅ sentry_config.php carregado com sucesso<br>";
        
        if (!empty($output)) {
            echo "⚠️ Output gerado: <pre>" . htmlspecialchars($output) . "</pre><br>";
        }
        
        // Verificar se funções foram definidas
        $funcoes = [
            'capturarErroCriticoAcademico',
            'capturarQueryLentaFACIC',
            'capturarSistemaForaDoArFACIC'
        ];
        
        foreach ($funcoes as $funcao) {
            if (function_exists($funcao)) {
                echo "✅ Função <strong>$funcao()</strong> definida<br>";
            } else {
                echo "❌ Função <strong>$funcao()</strong> NÃO definida<br>";
            }
        }
    } else {
        echo "❌ sentry_config.php não existe<br>";
    }
} catch (\Exception $e) {
    echo "❌ Erro ao carregar sentry_config.php: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";

// Teste 6: Tentar carregar conexao.php
echo "<h2>6. Teste de Carregamento do conexao.php</h2>";

try {
    // NÃO carregar conexao.php de verdade pois ele inicia sessão
    $conteudo = file_get_contents(__DIR__ . '/conexao.php');
    
    if (strpos($conteudo, 'sentry_config.php') !== false) {
        echo "✅ conexao.php contém referência ao Sentry<br>";
    } else {
        echo "❌ conexao.php NÃO contém referência ao Sentry<br>";
        echo "<strong>PROBLEMA IDENTIFICADO!</strong><br>";
    }
    
    // Verificar se tem erros de sintaxe
    $resultado = exec('php -l ' . __DIR__ . '/conexao.php 2>&1', $output, $return_var);
    
    if ($return_var === 0) {
        echo "✅ conexao.php sem erros de sintaxe<br>";
    } else {
        echo "❌ conexao.php tem erros de sintaxe:<br>";
        echo "<pre>" . implode("\n", $output) . "</pre>";
    }
    
} catch (\Exception $e) {
    echo "❌ Erro ao verificar conexao.php: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Teste 7: Teste de conexão com Sentry
echo "<h2>7. Teste de Conexão com Sentry</h2>";

try {
    if (class_exists('\Sentry\SentrySdk')) {
        // Tentar enviar uma mensagem de teste
        \Sentry\captureMessage('Teste de diagnóstico - ' . date('Y-m-d H:i:s'), \Sentry\Severity::info());
        echo "✅ Mensagem de teste enviada ao Sentry<br>";
        echo "Verifique o painel em: <a href='https://facic.sentry.io/projects/interativa-facic/' target='_blank'>https://facic.sentry.io/projects/interativa-facic/</a><br>";
    } else {
        echo "❌ Não foi possível testar conexão - Sentry não carregado<br>";
    }
} catch (\Exception $e) {
    echo "❌ Erro ao testar conexão: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Resumo
echo "<h2>8. Resumo do Diagnóstico</h2>";

$problemas = [];
$avisos = [];

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    $problemas[] = "vendor/autoload.php não existe - Execute: <code>composer install</code>";
}

if (version_compare(PHP_VERSION, '7.2.0', '<')) {
    $problemas[] = "Versão do PHP muito antiga. Sentry requer PHP 7.2+";
}

if (!extension_loaded('curl') || !extension_loaded('json') || !extension_loaded('mbstring')) {
    $problemas[] = "Extensões PHP necessárias não instaladas";
}

if (empty($problemas)) {
    echo "<p style='color: green; font-size: 18px;'><strong>✅ Nenhum problema crítico encontrado!</strong></p>";
    echo "<p>Se você ainda está vendo erro 500, verifique:</p>";
    echo "<ul>";
    echo "<li>Logs do servidor web (Apache/Nginx)</li>";
    echo "<li>Arquivo error_log do PHP</li>";
    echo "<li>Permissões dos arquivos</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red; font-size: 18px;'><strong>❌ Problemas encontrados:</strong></p>";
    echo "<ol>";
    foreach ($problemas as $problema) {
        echo "<li>$problema</li>";
    }
    echo "</ol>";
}

if (!empty($avisos)) {
    echo "<p style='color: orange;'><strong>⚠️ Avisos:</strong></p>";
    echo "<ul>";
    foreach ($avisos as $aviso) {
        echo "<li>$aviso</li>";
    }
    echo "</ul>";
}

echo "<hr>";
echo "<p><strong>Data/Hora do Diagnóstico:</strong> " . date('d/m/Y H:i:s') . "</p>";
echo "<p><strong>Servidor:</strong> " . ($_SERVER['SERVER_NAME'] ?? 'N/A') . "</p>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";

?>
