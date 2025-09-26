<?php
/**
 * Configuração do Sentry para Sistema Interativa FACIC
 * Projeto: Sistema Interativo FACIC - Plataforma Acadêmica
 *
 * Esta configuração segue as diretrizes dos agentes:
 * - Tech Lead: Decisões arquiteturais críticas para o sistema
 * - Dev Senior: Bugs críticos reportados pelo Sentry
 * - DevOps/SRE: Problemas de infraestrutura e servidor
 *
 * @see @Interativa\doc\agents\README.md
 */

require_once __DIR__ . '/vendor/autoload.php';

\Sentry\init([
    'dsn' => 'https://6804d2e8571595c2ccd363f2fe154868@o4510030147026944.ingest.us.sentry.io/4510030257061888',
    'traces_sample_rate' => 0.2,
    'profiles_sample_rate' => 0.1,

    // Configurações específicas para sistema legado PHP/MySQL
    'error_types' => E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED,
    'environment' => 'production',
    'release' => 'interativa-facic-v1.0.0',

    // Tags específicas para Sistema Interativa FACIC
    'tags' => [
        'sistema' => 'interativa-facic',
        'instituicao' => 'facic',
        'tipo' => 'sistema-academico',
        'plataforma' => 'php-mysql-legacy'
    ],

    // Configurações de performance para sistema legado
    'max_breadcrumbs' => 100,
    'attach_stacktrace' => true,
    'send_default_pii' => false, // Protege dados acadêmicos sensíveis

    // Configurações específicas para contexto acadêmico FACIC
    'before_send' => function (\Sentry\Event $event): ?\Sentry\Event {
        // Não enviar dados sensíveis de alunos/professores
        if ($event->getLevel() === \Sentry\Severity::debug()) {
            return null;
        }

        // Adicionar contexto acadêmico específico FACIC
        $event->setTags([
            'sistema' => 'interativa-facic',
            'categoria' => 'academico',
            'instituicao' => 'facic'
        ]);

        return $event;
    }
]);

/**
 * Função para capturar erros críticos do sistema acadêmico FACIC
 * Conforme agente Dev Senior: "Bugs críticos reportados pelo Sentry"
 */
function capturarErroCriticoAcademico(\Throwable $exception, array $contextoAcademico = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($contextoAcademico) {
        // Contexto específico acadêmico FACIC
        $scope->setTag('prioridade', 'critica-academica');
        $scope->setTag('agente_responsavel', 'dev-senior');
        $scope->setTag('instituicao', 'facic');

        foreach ($contextoAcademico as $key => $value) {
            $scope->setContext($key, $value);
        }

        // Contexto adicional para sistema acadêmico FACIC
        $scope->setContext('sistema_info', [
            'tipo' => 'interativa_facic',
            'ambiente' => 'producao_academica',
            'instituicao' => 'facic',
            'legado' => true
        ]);
    });

    \Sentry\captureException($exception);
}

/**
 * Função para capturar problemas de queries SQL lentas
 * Conforme agente Dev Senior: "Otimização de queries SQL lentas (>2 segundos)"
 */
function capturarQueryLentaFACIC(string $query, float $tempo, array $parametros = []) {
    $message = "Query SQL lenta no sistema FACIC: {$tempo}s";

    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($query, $tempo, $parametros) {
        $scope->setTag('tipo', 'query-lenta');
        $scope->setTag('agente_responsavel', 'dev-senior');
        $scope->setTag('instituicao', 'facic');

        $scope->setContext('query_performance', [
            'tempo_execucao' => $tempo,
            'limite_aceitavel' => 2.0,
            'query_hash' => md5($query), // Hash da query para agrupar
            'parametros_count' => count($parametros)
        ]);
    });

    if ($tempo > 2.0) {
        \Sentry\captureMessage($message, \Sentry\Severity::warning());
    }
}

/**
 * Função para monitoramento de sistema fora do ar
 * Conforme matriz de escalação: "Sistema fora do ar" - DevOps/SRE
 */
function capturarSistemaForaDoArFACIC(\Throwable $exception, array $dadosInfraestrutura = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($dadosInfraestrutura) {
        $scope->setTag('categoria', 'sistema-fora-do-ar');
        $scope->setTag('prioridade', 'critica');
        $scope->setTag('tempo_resposta', '15min');
        $scope->setTag('agente_principal', 'devops-sre');
        $scope->setTag('agente_suporte', 'tech-lead');
        $scope->setTag('instituicao', 'facic');

        $scope->setContext('infraestrutura', $dadosInfraestrutura);
    });

    \Sentry\captureException($exception);
}

/**
 * Função para capturar falhas de segurança
 * Conforme matriz de escalação: "Falha de segurança" - Tech Lead (Imediato)
 */
function capturarFalhaSegurancaFACIC(\Throwable $exception, array $dadosSeguranca = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($dadosSeguranca) {
        $scope->setTag('categoria', 'falha-seguranca');
        $scope->setTag('prioridade', 'critica-imediata');
        $scope->setTag('tempo_resposta', 'imediato');
        $scope->setTag('agente_principal', 'tech-lead');
        $scope->setTag('agente_suporte', 'devops-sre');
        $scope->setTag('instituicao', 'facic');

        // Não logar dados sensíveis de segurança
        $scope->setContext('seguranca', array_merge($dadosSeguranca, [
            'timestamp' => date('Y-m-d H:i:s'),
            'alerta_nivel' => 'critico'
        ]));
    });

    \Sentry\captureException($exception);
}

/**
 * Função para refatoração de código legado
 * Conforme agente Dev Senior: "Refatoração de código legado complexo"
 */
function capturarProblemaRefatoracaoFACIC(string $modulo, string $problema, array $contexto = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($modulo, $problema, $contexto) {
        $scope->setTag('categoria', 'refatoracao-legado');
        $scope->setTag('agente_responsavel', 'dev-senior');
        $scope->setTag('modulo', $modulo);
        $scope->setTag('instituicao', 'facic');

        $scope->setContext('refatoracao', [
            'modulo' => $modulo,
            'problema' => $problema,
            'contexto' => $contexto,
            'tipo_sistema' => 'legado_php_mysql'
        ]);
    });

    \Sentry\captureMessage(
        "Problema de refatoração no módulo {$modulo} FACIC: {$problema}",
        \Sentry\Severity::info()
    );
}

// Manter funções legadas para compatibilidade
function capturarErroSentry(\Throwable $exception, array $context = []) {
    capturarErroCriticoAcademico($exception, $context);
}

function capturarMensagemSentry(string $message, string $level = 'info', array $context = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($context) {
        $scope->setTag('instituicao', 'facic');
        foreach ($context as $key => $value) {
            $scope->setContext($key, $value);
        }
    });
    \Sentry\captureMessage($message, \Sentry\Severity::{$level}());
}

// Registrar handler global para erros não capturados FACIC
set_exception_handler(function (\Throwable $exception) {
    capturarErroCriticoAcademico($exception, [
        'handler' => 'global_exception_handler',
        'sistema' => 'interativa_facic',
        'instituicao' => 'facic'
    ]);
});

// Registrar handler para erros fatais FACIC
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR])) {
        \Sentry\captureMessage(
            "Erro fatal no sistema acadêmico FACIC: {$error['message']} em {$error['file']}:{$error['line']}",
            \Sentry\Severity::fatal()
        );
    }
});

// Log de inicialização FACIC
\Sentry\captureMessage(
    'Sistema Interativa FACIC inicializado com monitoramento Sentry',
    \Sentry\Severity::info()
);