<?php
/**
 * Configuração do Sentry para Sistema Interativa FASC Pindamonhangaba
 * Projeto: Sistema Interativo FASC - Plataforma Acadêmica Pindamonhangaba
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
    'dsn' => 'https://3c1395059387c9e9dc5927c8f26361f8@o4510030147026944.ingest.us.sentry.io/4510030262960128',
    'traces_sample_rate' => 1.0,
    'profiles_sample_rate' => 1.0,

    // Configurações específicas para sistema legado PHP/MySQL
    'error_types' => E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED,
    'environment' => 'production',
    'release' => 'interativa-fasc-pinda-v1.0.0',

    // Tags específicas para Sistema Interativa FASC Pindamonhangaba
    'tags' => [
        'sistema' => 'interativa-fasc',
        'instituicao' => 'fasc-pindamonhangaba',
        'cidade' => 'pindamonhangaba',
        'tipo' => 'sistema-academico',
        'plataforma' => 'php-mysql-legacy'
    ],

    // Configurações de performance para sistema legado
    'max_breadcrumbs' => 100,
    'attach_stacktrace' => true,
    'send_default_pii' => false, // Protege dados acadêmicos sensíveis

    // Configurações específicas para contexto acadêmico FASC Pindamonhangaba
    'before_send' => function (\Sentry\Event $event): ?\Sentry\Event {
        // Não enviar dados sensíveis de alunos/professores
        if ($event->getLevel() === \Sentry\Severity::debug()) {
            return null;
        }

        // Adicionar contexto acadêmico específico FASC Pindamonhangaba
        $event->setTags([
            'sistema' => 'interativa-fasc',
            'categoria' => 'academico',
            'instituicao' => 'fasc-pindamonhangaba',
            'cidade' => 'pindamonhangaba'
        ]);

        return $event;
    }
]);

/**
 * Função para capturar erros críticos do sistema acadêmico FASC Pindamonhangaba
 * Conforme agente Dev Senior: "Bugs críticos reportados pelo Sentry"
 */
function capturarErroCriticoAcademico(\Throwable $exception, array $contextoAcademico = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($contextoAcademico) {
        // Contexto específico acadêmico FASC Pindamonhangaba
        $scope->setTag('prioridade', 'critica-academica');
        $scope->setTag('agente_responsavel', 'dev-senior');
        $scope->setTag('instituicao', 'fasc-pindamonhangaba');
        $scope->setTag('cidade', 'pindamonhangaba');

        foreach ($contextoAcademico as $key => $value) {
            $scope->setContext($key, $value);
        }

        // Contexto adicional para sistema acadêmico FASC Pindamonhangaba
        $scope->setContext('sistema_info', [
            'tipo' => 'interativa_fasc',
            'ambiente' => 'producao_academica',
            'instituicao' => 'fasc-pindamonhangaba',
            'cidade' => 'pindamonhangaba',
            'legado' => true
        ]);
    });

    \Sentry\captureException($exception);
}

/**
 * Função para capturar problemas específicos de biblioteca digital
 * Conforme matriz de escalação: "Biblioteca digital inacessível" - Dev Senior
 */
function capturarFalhaBibliotecaDigitalFASC(\Throwable $exception, array $dadosBiblioteca = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($dadosBiblioteca) {
        $scope->setTag('categoria', 'biblioteca-digital');
        $scope->setTag('prioridade', 'alta');
        $scope->setTag('tempo_resposta', '2horas');
        $scope->setTag('agente_principal', 'dev-senior');
        $scope->setTag('agente_suporte', 'qa-engineer');
        $scope->setTag('instituicao', 'fasc-pindamonhangaba');

        $scope->setContext('biblioteca_digital', $dadosBiblioteca);
    });

    \Sentry\captureException($exception);
}

/**
 * Função para capturar problemas de perda de dados reportada
 * Conforme matriz de escalação: "Perda de dados reportada" - DevOps/SRE
 */
function capturarPerdaDadosFASC(\Throwable $exception, array $dadosPerda = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($dadosPerda) {
        $scope->setTag('categoria', 'perda-dados');
        $scope->setTag('prioridade', 'critica');
        $scope->setTag('tempo_resposta', '30min');
        $scope->setTag('agente_principal', 'devops-sre');
        $scope->setTag('agente_suporte', 'gerente-projeto');
        $scope->setTag('instituicao', 'fasc-pindamonhangaba');

        $scope->setContext('perda_dados', array_merge($dadosPerda, [
            'timestamp_incidente' => date('Y-m-d H:i:s'),
            'necessita_backup_recovery' => true
        ]));
    });

    \Sentry\captureException($exception);
}

/**
 * Função para monitoramento de funcionalidade crítica quebrada
 * Conforme matriz de escalação: "Funcionalidade crítica quebrada" - Dev Senior
 */
function capturarFuncionalidadeCriticaQuebradaFASC(\Throwable $exception, string $funcionalidade, array $detalhes = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($funcionalidade, $detalhes) {
        $scope->setTag('categoria', 'funcionalidade-critica-quebrada');
        $scope->setTag('prioridade', 'alta');
        $scope->setTag('tempo_resposta', '4horas');
        $scope->setTag('agente_principal', 'dev-senior');
        $scope->setTag('agente_suporte', 'qa-engineer');
        $scope->setTag('funcionalidade', $funcionalidade);
        $scope->setTag('instituicao', 'fasc-pindamonhangaba');

        $scope->setContext('funcionalidade_quebrada', [
            'nome' => $funcionalidade,
            'detalhes' => $detalhes,
            'impacto_usuarios' => 'alto',
            'requer_rollback' => false
        ]);
    });

    \Sentry\captureException($exception);
}

/**
 * Função para capturar deadline educacional em risco
 * Conforme matriz de escalação: "Deadline educacional em risco" - Gerente Projeto
 */
function capturarDeadlineEducacionalRiscoFASC(string $projeto, string $deadline, array $riscos = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($projeto, $deadline, $riscos) {
        $scope->setTag('categoria', 'deadline-em-risco');
        $scope->setTag('prioridade', 'alta');
        $scope->setTag('tempo_resposta', '12horas');
        $scope->setTag('agente_principal', 'gerente-projeto');
        $scope->setTag('agente_suporte', 'tech-lead');
        $scope->setTag('projeto', $projeto);
        $scope->setTag('instituicao', 'fasc-pindamonhangaba');

        $scope->setContext('deadline_risco', [
            'projeto' => $projeto,
            'deadline' => $deadline,
            'riscos_identificados' => $riscos,
            'necessita_reuniao_urgente' => true
        ]);
    });

    \Sentry\captureMessage(
        "Deadline educacional em risco - Projeto {$projeto} FASC Pindamonhangaba: {$deadline}",
        \Sentry\Severity::warning()
    );
}

// Manter funções legadas para compatibilidade
function capturarErroSentry(\Throwable $exception, array $context = []) {
    capturarErroCriticoAcademico($exception, $context);
}

function capturarMensagemSentry(string $message, string $level = 'info', array $context = []) {
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($context) {
        $scope->setTag('instituicao', 'fasc-pindamonhangaba');
        $scope->setTag('cidade', 'pindamonhangaba');
        foreach ($context as $key => $value) {
            $scope->setContext($key, $value);
        }
    });
    \Sentry\captureMessage($message, \Sentry\Severity::{$level}());
}

// Registrar handler global para erros não capturados FASC Pindamonhangaba
set_exception_handler(function (\Throwable $exception) {
    capturarErroCriticoAcademico($exception, [
        'handler' => 'global_exception_handler',
        'sistema' => 'interativa_fasc',
        'instituicao' => 'fasc-pindamonhangaba',
        'cidade' => 'pindamonhangaba'
    ]);
});

// Registrar handler para erros fatais FASC Pindamonhangaba
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR])) {
        \Sentry\captureMessage(
            "Erro fatal no sistema acadêmico FASC Pindamonhangaba: {$error['message']} em {$error['file']}:{$error['line']}",
            \Sentry\Severity::fatal()
        );
    }
});

// Log de inicialização FASC Pindamonhangaba
\Sentry\captureMessage(
    'Sistema Interativa FASC Pindamonhangaba inicializado com monitoramento Sentry',
    \Sentry\Severity::info()
);