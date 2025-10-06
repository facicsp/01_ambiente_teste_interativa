<?php
/**
 * EXEMPLO PRÁTICO - Como usar Sentry no sistema FACIC Interativa
 * 
 * Este arquivo demonstra as melhores práticas para integrar
 * o monitoramento Sentry em operações do sistema acadêmico.
 * 
 * @author Sistema de Monitoramento via MCP
 * @date 2025-10-03
 */

// ============================================
// IMPORTANTE: conexao.php JÁ CARREGA O SENTRY
// ============================================
// Você não precisa incluir sentry_config.php manualmente
// O arquivo conexao.php já faz isso automaticamente!
require_once 'conexao.php';

// ============================================
// EXEMPLO 1: Salvar Nota de Aluno
// ============================================

function salvarNotaAluno($idAluno, $idDisciplina, $nota) {
    try {
        // Validação básica
        if (!is_numeric($nota) || $nota < 0 || $nota > 10) {
            throw new Exception("Nota inválida: deve estar entre 0 e 10");
        }
        
        // Query SQL
        $sql = "INSERT INTO notas (idAluno, idDisciplina, nota, data) 
                VALUES ('$idAluno', '$idDisciplina', '$nota', NOW())";
        
        $result = mysql_query($sql);
        
        if (!$result) {
            throw new Exception("Erro ao salvar nota: " . mysql_error());
        }
        
        return true;
        
    } catch (Exception $e) {
        // Capturar erro com contexto acadêmico
        capturarErroCriticoAcademico($e, [
            'modulo' => 'notas',
            'operacao' => 'salvar_nota',
            'aluno_id' => $idAluno,
            'disciplina_id' => $idDisciplina,
            'nota' => $nota,
            'sql' => isset($sql) ? $sql : 'N/A'
        ]);
        
        // Retornar false ou exibir mensagem ao usuário
        echo "Erro ao salvar nota. Nossa equipe foi notificada.";
        return false;
    }
}

// ============================================
// EXEMPLO 2: Monitorar Query Lenta
// ============================================

function buscarHistoricoAluno($idAluno) {
    $inicio = microtime(true);
    
    $sql = "SELECT 
                aluno.nome,
                disciplina.disciplina,
                notas.nota,
                notas.data
            FROM aluno
            JOIN matricula ON aluno.idAluno = matricula.idAluno
            JOIN disciplina ON matricula.idTurma = disciplina.idTurma
            LEFT JOIN notas ON aluno.idAluno = notas.idAluno
            WHERE aluno.idAluno = '$idAluno'
            ORDER BY notas.data DESC";
    
    $result = mysql_query($sql);
    $tempo = microtime(true) - $inicio;
    
    // Capturar queries que demoram mais de 2 segundos
    if ($tempo > 2.0) {
        capturarQueryLentaFACIC($sql, $tempo, [
            'tabela_principal' => 'aluno',
            'operacao' => 'buscar_historico',
            'aluno_id' => $idAluno,
            'numero_joins' => 3
        ]);
    }
    
    return $result;
}

// ============================================
// EXEMPLO 3: Capturar Problema de Autenticação
// ============================================

function validarLoginAluno($usuario, $senha) {
    try {
        $sql = "SELECT * FROM usuario 
                WHERE login = '$usuario' 
                AND senha = MD5('$senha')
                AND tipo = 'aluno'
                LIMIT 1";
        
        $result = mysql_query($sql);
        
        if (!$result) {
            throw new Exception("Erro na query de autenticação: " . mysql_error());
        }
        
        $linhas = mysql_num_rows($result);
        
        if ($linhas == 0) {
            // Tentativa de login falhou - registrar no Sentry
            \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($usuario) {
                $scope->setTag('tipo', 'falha_autenticacao');
                $scope->setTag('usuario_tentativa', $usuario);
                $scope->setTag('ip', $_SERVER['REMOTE_ADDR'] ?? 'N/A');
            });
            
            \Sentry\captureMessage(
                "Tentativa de login falhou para usuário: $usuario",
                \Sentry\Severity::warning()
            );
            
            return false;
        }
        
        return mysql_fetch_assoc($result);
        
    } catch (Exception $e) {
        capturarFalhaSegurancaFACIC($e, [
            'tipo' => 'erro_autenticacao',
            'usuario' => $usuario,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'N/A',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'N/A'
        ]);
        
        return false;
    }
}

// ============================================
// EXEMPLO 4: Monitorar Upload de Arquivo
// ============================================

function processarUploadAtividade($idAluno, $idAtividade, $arquivo) {
    try {
        // Validar tamanho do arquivo
        $tamanhoMax = 10 * 1024 * 1024; // 10MB
        
        if ($arquivo['size'] > $tamanhoMax) {
            throw new Exception("Arquivo muito grande: " . 
                              ($arquivo['size'] / 1024 / 1024) . "MB");
        }
        
        // Validar tipo de arquivo
        $tiposPermitidos = ['pdf', 'doc', 'docx', 'jpg', 'png'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extensao, $tiposPermitidos)) {
            throw new Exception("Tipo de arquivo não permitido: $extensao");
        }
        
        // Processar upload
        $nomeArquivo = "Atividade{$idAtividade}-{$idAluno}_" . 
                       time() . "." . $extensao;
        $destino = "atividades/" . $nomeArquivo;
        
        if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
            throw new Exception("Erro ao mover arquivo para: $destino");
        }
        
        // Salvar no banco
        $sql = "INSERT INTO envios (idAluno, idAtividade, arquivo, data) 
                VALUES ('$idAluno', '$idAtividade', '$nomeArquivo', NOW())";
        
        if (!mysql_query($sql)) {
            throw new Exception("Erro ao salvar no banco: " . mysql_error());
        }
        
        return $nomeArquivo;
        
    } catch (Exception $e) {
        // Capturar erro com contexto completo
        capturarErroCriticoAcademico($e, [
            'modulo' => 'atividades',
            'operacao' => 'upload_arquivo',
            'aluno_id' => $idAluno,
            'atividade_id' => $idAtividade,
            'arquivo_nome' => $arquivo['name'] ?? 'N/A',
            'arquivo_tamanho' => $arquivo['size'] ?? 'N/A',
            'arquivo_tipo' => $arquivo['type'] ?? 'N/A',
            'extensao' => isset($extensao) ? $extensao : 'N/A'
        ]);
        
        echo "Erro ao processar upload. Tente novamente.";
        return false;
    }
}

// ============================================
// EXEMPLO 5: Operação de Matrícula Crítica
// ============================================

function matricularAluno($idAluno, $idTurma, $idDisciplina) {
    try {
        // Iniciar transação (simulado, pois mysql_* não suporta nativamente)
        mysql_query("START TRANSACTION");
        
        // Verificar se já está matriculado
        $sqlVerifica = "SELECT * FROM matricula 
                        WHERE idAluno = '$idAluno' 
                        AND idTurma = '$idTurma'";
        $result = mysql_query($sqlVerifica);
        
        if (mysql_num_rows($result) > 0) {
            throw new Exception("Aluno já matriculado nesta turma");
        }
        
        // Inserir matrícula
        $sqlMatricula = "INSERT INTO matricula 
                         (idAluno, idTurma, idDisciplina, data, ativo) 
                         VALUES 
                         ('$idAluno', '$idTurma', '$idDisciplina', NOW(), 'sim')";
        
        if (!mysql_query($sqlMatricula)) {
            throw new Exception("Erro ao inserir matrícula: " . mysql_error());
        }
        
        // Atualizar contador de vagas
        $sqlVagas = "UPDATE turma 
                     SET vagas_ocupadas = vagas_ocupadas + 1 
                     WHERE idTurma = '$idTurma'";
        
        if (!mysql_query($sqlVagas)) {
            throw new Exception("Erro ao atualizar vagas: " . mysql_error());
        }
        
        // Commit
        mysql_query("COMMIT");
        
        // Log de sucesso no Sentry (informativo)
        \Sentry\captureMessage(
            "Matrícula realizada com sucesso: Aluno $idAluno na Turma $idTurma",
            \Sentry\Severity::info()
        );
        
        return true;
        
    } catch (Exception $e) {
        // Rollback em caso de erro
        mysql_query("ROLLBACK");
        
        // Capturar erro crítico de matrícula
        capturarErroCriticoAcademico($e, [
            'modulo' => 'matricula',
            'operacao' => 'matricular_aluno',
            'aluno_id' => $idAluno,
            'turma_id' => $idTurma,
            'disciplina_id' => $idDisciplina,
            'tipo_erro' => 'operacao_critica_academica'
        ]);
        
        echo "Erro ao realizar matrícula. Contate a secretaria.";
        return false;
    }
}

// ============================================
// EXEMPLO 6: Capturar Informações de Debug
// ============================================

function registrarAcessoAluno($idAluno) {
    try {
        // Capturar breadcrumbs (rastro de ações)
        \Sentry\addBreadcrumb(
            new \Sentry\Breadcrumb(
                \Sentry\Breadcrumb::LEVEL_INFO,
                \Sentry\Breadcrumb::TYPE_USER,
                'acesso',
                'Aluno acessou o sistema',
                [
                    'aluno_id' => $idAluno,
                    'timestamp' => date('Y-m-d H:i:s'),
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'N/A'
                ]
            )
        );
        
        // Salvar no banco
        $sql = "INSERT INTO acessos (idAluno, data, ip) 
                VALUES ('$idAluno', NOW(), '{$_SERVER['REMOTE_ADDR']}')";
        
        mysql_query($sql);
        
    } catch (Exception $e) {
        // Erro não crítico - apenas log
        \Sentry\captureException($e);
    }
}

// ============================================
// EXEMPLO 7: Uso em Handler de AJAX
// ============================================

// Se este arquivo for chamado via AJAX
if (isset($_POST['acao'])) {
    $acao = $_POST['acao'];
    
    try {
        switch ($acao) {
            case 'salvar_nota':
                $resultado = salvarNotaAluno(
                    $_POST['idAluno'],
                    $_POST['idDisciplina'],
                    $_POST['nota']
                );
                echo json_encode(['sucesso' => $resultado]);
                break;
                
            case 'matricular':
                $resultado = matricularAluno(
                    $_POST['idAluno'],
                    $_POST['idTurma'],
                    $_POST['idDisciplina']
                );
                echo json_encode(['sucesso' => $resultado]);
                break;
                
            default:
                throw new Exception("Ação desconhecida: $acao");
        }
        
    } catch (Exception $e) {
        // Capturar erro em operação AJAX
        capturarErroCriticoAcademico($e, [
            'modulo' => 'ajax',
            'acao' => $acao,
            'post_data' => $_POST,
            'origem' => 'requisicao_ajax'
        ]);
        
        echo json_encode([
            'sucesso' => false,
            'erro' => 'Erro ao processar requisição'
        ]);
    }
}

?>
