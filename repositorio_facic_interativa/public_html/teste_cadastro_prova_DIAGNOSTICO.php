<?php
/**
 * SCRIPT DE DIAGNÓSTICO COMPLETO - Sistema de Cadastro de Provas
 *
 * Este script executa uma bateria completa de testes para validar:
 * 1. Conexão com banco de dados
 * 2. Estrutura das tabelas
 * 3. Simulação de cadastro de prova e questões
 * 4. Validação de dados salvos
 * 5. Verificação de permissões de diretórios
 *
 * ATENÇÃO: Execute este script APENAS em ambiente de testes!
 *
 * Como usar:
 * 1. Acesse: http://www.facicinterativa.com.br/ambiente_QA/teste_cadastro_prova_DIAGNOSTICO.php
 * 2. Aguarde a execução completa
 * 3. Revise o relatório gerado
 *
 * Data: 01/10/2025
 */

session_start();

// Simular sessão de professor para testes
if (!isset($_SESSION["usuario"])) {
    $_SESSION["usuario"] = "teste_qa";
    $_SESSION["tipo"] = "professor";
    $_SESSION["id"] = 9999; // ID fictício para testes
    echo "<p><strong>⚠️ AVISO:</strong> Sessão de teste criada automaticamente.</p>";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Diagnóstico - Cadastro de Provas</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
            border-left: 4px solid #3498db;
            padding-left: 10px;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 12px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 12px;
            margin: 10px 0;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #34495e;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .test-step {
            background: #ecf0f1;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }
        .badge-success { background: #28a745; color: white; }
        .badge-error { background: #dc3545; color: white; }
        .badge-warning { background: #ffc107; color: #000; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnóstico Completo - Sistema de Cadastro de Provas</h1>
        <p><strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
        <p><strong>Servidor:</strong> <?php echo $_SERVER['SERVER_NAME']; ?></p>

        <?php

        $resultados = [
            'total' => 0,
            'sucesso' => 0,
            'erro' => 0,
            'aviso' => 0
        ];

        // ================================================================
        // TESTE 1: Conexão com Banco de Dados
        // ================================================================
        echo "<h2>📊 TESTE 1: Conexão com Banco de Dados</h2>";
        echo "<div class='test-step'>";

        $resultados['total']++;

        try {
            include 'conexao.php';

            if ($conexao) {
                echo "<div class='success'>";
                echo "<strong>✓ SUCESSO:</strong> Conexão com banco de dados estabelecida!<br>";
                echo "<strong>Host:</strong> interativa.vpshost0360.mysql.dbaas.com.br<br>";
                echo "<strong>Database:</strong> interativa";
                echo "</div>";
                $resultados['sucesso']++;
            } else {
                throw new Exception("Conexão retornou false");
            }
        } catch (Exception $e) {
            echo "<div class='error'>";
            echo "<strong>✗ ERRO:</strong> Falha na conexão com banco de dados<br>";
            echo "<strong>Detalhes:</strong> " . $e->getMessage();
            echo "</div>";
            $resultados['erro']++;
        }

        echo "</div>";

        // ================================================================
        // TESTE 2: Estrutura das Tabelas
        // ================================================================
        echo "<h2>🗂️ TESTE 2: Estrutura das Tabelas</h2>";

        $tabelas_necessarias = ['prova', 'questao2', 'alternativa'];

        foreach ($tabelas_necessarias as $tabela) {
            echo "<div class='test-step'>";
            echo "<h3>Tabela: <code>$tabela</code></h3>";

            $resultados['total']++;

            $query = "SHOW TABLES LIKE '$tabela'";
            $resultado = mysql_query($query);

            if ($resultado && mysql_num_rows($resultado) > 0) {
                echo "<div class='success'><strong>✓</strong> Tabela existe</div>";

                // Mostrar estrutura
                $query_estrutura = "DESCRIBE $tabela";
                $resultado_estrutura = mysql_query($query_estrutura);

                if ($resultado_estrutura) {
                    echo "<table>";
                    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th></tr>";

                    while ($row = mysql_fetch_assoc($resultado_estrutura)) {
                        echo "<tr>";
                        echo "<td><strong>" . $row['Field'] . "</strong></td>";
                        echo "<td>" . $row['Type'] . "</td>";
                        echo "<td>" . $row['Null'] . "</td>";
                        echo "<td>" . $row['Key'] . "</td>";
                        echo "<td>" . ($row['Default'] ? $row['Default'] : '-') . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                }

                $resultados['sucesso']++;
            } else {
                echo "<div class='error'><strong>✗ ERRO:</strong> Tabela não encontrada!</div>";
                $resultados['erro']++;
            }

            echo "</div>";
        }

        // ================================================================
        // TESTE 3: Diretórios e Permissões
        // ================================================================
        echo "<h2>📁 TESTE 3: Diretórios e Permissões</h2>";
        echo "<div class='test-step'>";

        $diretorios = [
            'uploads' => 'uploads',
            'uploads/anexos' => 'uploads/anexos'
        ];

        foreach ($diretorios as $nome => $caminho) {
            $resultados['total']++;

            echo "<h3>Diretório: <code>$caminho</code></h3>";

            if (is_dir($caminho)) {
                $permissoes = substr(sprintf('%o', fileperms($caminho)), -4);
                $pode_escrever = is_writable($caminho);

                if ($pode_escrever) {
                    echo "<div class='success'>";
                    echo "<strong>✓ SUCESSO:</strong> Diretório existe e tem permissão de escrita<br>";
                    echo "<strong>Permissões:</strong> $permissoes<br>";
                    echo "<strong>Caminho absoluto:</strong> " . realpath($caminho);
                    echo "</div>";
                    $resultados['sucesso']++;
                } else {
                    echo "<div class='warning'>";
                    echo "<strong>⚠ AVISO:</strong> Diretório existe mas NÃO tem permissão de escrita<br>";
                    echo "<strong>Permissões:</strong> $permissoes<br>";
                    echo "<strong>Solução:</strong> chmod 755 $caminho";
                    echo "</div>";
                    $resultados['aviso']++;
                }
            } else {
                echo "<div class='error'>";
                echo "<strong>✗ ERRO:</strong> Diretório não existe!<br>";
                echo "<strong>Solução:</strong> mkdir -p $caminho";
                echo "</div>";
                $resultados['erro']++;
            }
        }

        echo "</div>";

        // ================================================================
        // TESTE 4: Classe Seguranca
        // ================================================================
        echo "<h2>🔒 TESTE 4: Classe Seguranca</h2>";
        echo "<div class='test-step'>";

        $resultados['total']++;

        if (class_exists('Seguranca')) {
            $seguranca = new Seguranca();

            // Testar método antisql
            $teste_string = "teste' OR '1'='1";
            $resultado_sanitizacao = $seguranca->antisql($teste_string);

            echo "<div class='success'>";
            echo "<strong>✓ SUCESSO:</strong> Classe Seguranca carregada<br>";
            echo "<strong>Teste de sanitização:</strong><br>";
            echo "Input: <code>$teste_string</code><br>";
            echo "Output: <code>$resultado_sanitizacao</code>";
            echo "</div>";

            $resultados['sucesso']++;
        } else {
            echo "<div class='error'>";
            echo "<strong>✗ ERRO:</strong> Classe Seguranca não encontrada!";
            echo "</div>";
            $resultados['erro']++;
        }

        echo "</div>";

        // ================================================================
        // TESTE 5: Simulação de Cadastro Completo
        // ================================================================
        echo "<h2>💾 TESTE 5: Simulação de Cadastro de Prova</h2>";
        echo "<div class='test-step'>";

        // Dados de teste
        $titulo_teste = "PROVA TESTE QA - " . date('YmdHis');
        $idProfessor_teste = $_SESSION["id"];

        echo "<h3>Passo 1: Criar Prova</h3>";
        $resultados['total']++;

        $query_insert_prova = "INSERT INTO prova VALUES (NULL, '$titulo_teste', '$idProfessor_teste')";
        $result_insert_prova = mysql_query($query_insert_prova);

        if ($result_insert_prova) {
            // Buscar ID da prova criada
            $query_select_prova = "SELECT idProva FROM prova
                                  WHERE titulo = '$titulo_teste'
                                  AND idProfessor = '$idProfessor_teste'
                                  ORDER BY idProva DESC LIMIT 1";
            $result_select_prova = mysql_query($query_select_prova);

            if ($result_select_prova && mysql_num_rows($result_select_prova) > 0) {
                $idProva_teste = mysql_result($result_select_prova, 0, 'idProva');

                echo "<div class='success'>";
                echo "<strong>✓ SUCESSO:</strong> Prova criada<br>";
                echo "<strong>ID da Prova:</strong> $idProva_teste<br>";
                echo "<strong>Título:</strong> $titulo_teste";
                echo "</div>";

                $resultados['sucesso']++;

                // -------- Criar Questão --------
                echo "<h3>Passo 2: Criar Questão</h3>";
                $resultados['total']++;

                $descricao_teste = "Questão de teste criada em " . date('d/m/Y H:i:s');
                $tipo_teste = "objetiva";
                $peso_teste = 1;

                $query_insert_questao = "INSERT INTO questao2 VALUES (NULL, '$descricao_teste', '$tipo_teste', '$peso_teste', '$idProva_teste')";
                $result_insert_questao = mysql_query($query_insert_questao);

                if ($result_insert_questao) {
                    // Buscar ID da questão criada
                    $query_select_questao = "SELECT idQuestao FROM questao2
                                            WHERE descricao = '$descricao_teste'
                                            AND idProva = '$idProva_teste'
                                            ORDER BY idQuestao DESC LIMIT 1";
                    $result_select_questao = mysql_query($query_select_questao);

                    if ($result_select_questao && mysql_num_rows($result_select_questao) > 0) {
                        $idQuestao_teste = mysql_result($result_select_questao, 0, 'idQuestao');

                        echo "<div class='success'>";
                        echo "<strong>✓ SUCESSO:</strong> Questão criada<br>";
                        echo "<strong>ID da Questão:</strong> $idQuestao_teste<br>";
                        echo "<strong>Descrição:</strong> $descricao_teste";
                        echo "</div>";

                        $resultados['sucesso']++;

                        // -------- Criar Alternativas --------
                        echo "<h3>Passo 3: Criar Alternativas</h3>";

                        $alternativas_teste = [
                            ['texto' => 'Alternativa A - Teste', 'correta' => 'sim'],
                            ['texto' => 'Alternativa B - Teste', 'correta' => 'nao'],
                            ['texto' => 'Alternativa C - Teste', 'correta' => 'nao'],
                            ['texto' => 'Alternativa D - Teste', 'correta' => 'nao']
                        ];

                        $alternativas_criadas = 0;

                        foreach ($alternativas_teste as $index => $alt) {
                            $resultados['total']++;

                            $query_insert_alt = "INSERT INTO alternativa VALUES (NULL, '{$alt['texto']}', '{$alt['correta']}', '$idQuestao_teste')";
                            $result_insert_alt = mysql_query($query_insert_alt);

                            if ($result_insert_alt) {
                                $alternativas_criadas++;
                                $resultados['sucesso']++;
                            } else {
                                echo "<div class='error'>";
                                echo "<strong>✗ ERRO:</strong> Falha ao criar alternativa " . ($index + 1) . "<br>";
                                echo "<strong>MySQL Error:</strong> " . mysql_error();
                                echo "</div>";
                                $resultados['erro']++;
                            }
                        }

                        if ($alternativas_criadas == count($alternativas_teste)) {
                            echo "<div class='success'>";
                            echo "<strong>✓ SUCESSO:</strong> Todas as $alternativas_criadas alternativas foram criadas";
                            echo "</div>";
                        }

                        // -------- Validação Final --------
                        echo "<h3>Passo 4: Validação dos Dados Salvos</h3>";

                        // Verificar se prova tem questões
                        $query_count = "SELECT COUNT(*) as total FROM questao2 WHERE idProva = '$idProva_teste'";
                        $result_count = mysql_query($query_count);
                        $total_questoes = mysql_result($result_count, 0, 'total');

                        // Verificar se questão tem alternativas
                        $query_count_alt = "SELECT COUNT(*) as total FROM alternativa WHERE idQuestao = '$idQuestao_teste'";
                        $result_count_alt = mysql_query($query_count_alt);
                        $total_alternativas = mysql_result($result_count_alt, 0, 'total');

                        echo "<div class='info'>";
                        echo "<strong>📊 Resumo:</strong><br>";
                        echo "Prova ID $idProva_teste tem <strong>$total_questoes questão(ões)</strong><br>";
                        echo "Questão ID $idQuestao_teste tem <strong>$total_alternativas alternativa(s)</strong>";
                        echo "</div>";

                        // -------- Limpeza (OPCIONAL) --------
                        echo "<h3>Passo 5: Limpeza dos Dados de Teste</h3>";

                        echo "<div class='warning'>";
                        echo "<strong>⚠️ ATENÇÃO:</strong> Deseja excluir os dados de teste criados?<br>";
                        echo "<form method='post' style='margin-top: 10px;'>";
                        echo "<input type='hidden' name='limpar_teste' value='1'>";
                        echo "<input type='hidden' name='idProva' value='$idProva_teste'>";
                        echo "<input type='hidden' name='idQuestao' value='$idQuestao_teste'>";
                        echo "<button type='submit' style='background: #e74c3c; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;'>Excluir Dados de Teste</button>";
                        echo "</form>";
                        echo "</div>";

                    } else {
                        echo "<div class='error'><strong>✗ ERRO:</strong> Questão não foi encontrada após inserção!</div>";
                        $resultados['erro']++;
                    }
                } else {
                    echo "<div class='error'>";
                    echo "<strong>✗ ERRO:</strong> Falha ao criar questão<br>";
                    echo "<strong>MySQL Error:</strong> " . mysql_error();
                    echo "</div>";
                    $resultados['erro']++;
                }

            } else {
                echo "<div class='error'><strong>✗ ERRO:</strong> Prova não foi encontrada após inserção!</div>";
                $resultados['erro']++;
            }
        } else {
            echo "<div class='error'>";
            echo "<strong>✗ ERRO:</strong> Falha ao criar prova<br>";
            echo "<strong>MySQL Error:</strong> " . mysql_error();
            echo "</div>";
            $resultados['erro']++;
        }

        echo "</div>";

        // Processar limpeza se solicitado
        if (isset($_POST['limpar_teste']) && $_POST['limpar_teste'] == '1') {
            $idProva = $_POST['idProva'];
            $idQuestao = $_POST['idQuestao'];

            echo "<div class='test-step'>";
            echo "<h3>🗑️ Limpeza de Dados de Teste</h3>";

            // Excluir alternativas
            mysql_query("DELETE FROM alternativa WHERE idQuestao = '$idQuestao'");

            // Excluir questão
            mysql_query("DELETE FROM questao2 WHERE idQuestao = '$idQuestao'");

            // Excluir prova
            mysql_query("DELETE FROM prova WHERE idProva = '$idProva'");

            echo "<div class='success'><strong>✓</strong> Dados de teste excluídos com sucesso!</div>";
            echo "</div>";
        }

        // ================================================================
        // TESTE 6: Verificar SELECT de Provas Disponíveis
        // ================================================================
        echo "<h2>📋 TESTE 6: Listar Provas Disponíveis</h2>";
        echo "<div class='test-step'>";

        $resultados['total']++;

        // Query similar ao cadastroProva2.php
        $query_provas = "SELECT p.idProva, p.titulo, COUNT(q2.idQuestao) as total_questoes
                        FROM prova p
                        LEFT JOIN questao2 q2 ON p.idProva = q2.idProva
                        GROUP BY p.idProva, p.titulo
                        HAVING COUNT(q2.idQuestao) > 0
                        ORDER BY p.idProva DESC
                        LIMIT 10";

        $result_provas = mysql_query($query_provas);

        if ($result_provas && mysql_num_rows($result_provas) > 0) {
            echo "<div class='success'><strong>✓</strong> Provas encontradas</div>";

            echo "<table>";
            echo "<tr><th>ID</th><th>Título</th><th>Questões</th></tr>";

            while ($prova = mysql_fetch_assoc($result_provas)) {
                echo "<tr>";
                echo "<td>" . $prova['idProva'] . "</td>";
                echo "<td>" . $prova['titulo'] . "</td>";
                echo "<td><strong>" . $prova['total_questoes'] . "</strong></td>";
                echo "</tr>";
            }

            echo "</table>";

            $resultados['sucesso']++;
        } else {
            echo "<div class='warning'>";
            echo "<strong>⚠️ AVISO:</strong> Nenhuma prova com questões encontrada<br>";
            echo "Isto pode ser normal se o sistema ainda não tem provas cadastradas.";
            echo "</div>";
            $resultados['aviso']++;
        }

        echo "</div>";

        // ================================================================
        // RELATÓRIO FINAL
        // ================================================================
        echo "<h2>📈 RELATÓRIO FINAL</h2>";
        echo "<div class='test-step'>";

        $taxa_sucesso = $resultados['total'] > 0 ? ($resultados['sucesso'] / $resultados['total']) * 100 : 0;

        echo "<table style='font-size: 16px;'>";
        echo "<tr><th>Métrica</th><th>Quantidade</th></tr>";
        echo "<tr><td>Total de Testes</td><td><strong>{$resultados['total']}</strong></td></tr>";
        echo "<tr style='background: #d4edda;'><td>✓ Sucessos</td><td><strong>{$resultados['sucesso']}</strong></td></tr>";
        echo "<tr style='background: #f8d7da;'><td>✗ Erros</td><td><strong>{$resultados['erro']}</strong></td></tr>";
        echo "<tr style='background: #fff3cd;'><td>⚠ Avisos</td><td><strong>{$resultados['aviso']}</strong></td></tr>";
        echo "<tr style='background: #d1ecf1;'><td>Taxa de Sucesso</td><td><strong>" . number_format($taxa_sucesso, 1) . "%</strong></td></tr>";
        echo "</table>";

        // Veredicto final
        echo "<div style='margin-top: 20px; padding: 20px; border: 3px solid; border-radius: 8px; text-align: center; font-size: 18px; font-weight: bold;'";

        if ($resultados['erro'] == 0 && $taxa_sucesso >= 90) {
            echo " style='border-color: #28a745; background: #d4edda; color: #155724;'>";
            echo "🎉 SISTEMA FUNCIONANDO PERFEITAMENTE!";
        } elseif ($resultados['erro'] > 0 && $resultados['erro'] < 3) {
            echo " style='border-color: #ffc107; background: #fff3cd; color: #856404;'>";
            echo "⚠️ SISTEMA FUNCIONAL MAS COM ALERTAS";
        } else {
            echo " style='border-color: #dc3545; background: #f8d7da; color: #721c24;'>";
            echo "❌ SISTEMA COM PROBLEMAS CRÍTICOS";
        }

        echo "</div>";

        echo "</div>";

        // ================================================================
        // RECOMENDAÇÕES
        // ================================================================
        if ($resultados['erro'] > 0 || $resultados['aviso'] > 0) {
            echo "<h2>💡 RECOMENDAÇÕES</h2>";
            echo "<div class='test-step'>";
            echo "<ul style='line-height: 1.8;'>";

            if ($resultados['erro'] > 0) {
                echo "<li>❌ <strong>Corrija os erros críticos</strong> identificados acima antes de usar o sistema em produção</li>";
            }

            echo "<li>📖 Consulte a documentação em <code>00.docs_correcoes/0110205-correcao-cadastro-provas/</code></li>";
            echo "<li>🔍 Execute este diagnóstico regularmente após mudanças no sistema</li>";
            echo "<li>🔐 Considere migrar de <code>mysql_*</code> para <code>mysqli</code> ou <code>PDO</code> (PHP 7+ compatível)</li>";
            echo "<li>🛡️ Implemente prepared statements para prevenir SQL injection</li>";

            echo "</ul>";
            echo "</div>";
        }

        ?>

        <div style="margin-top: 30px; padding: 15px; background: #ecf0f1; border-radius: 4px; text-align: center;">
            <p><strong>Diagnóstico finalizado em:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
            <p><strong>Versão do Script:</strong> 1.0 | <strong>Data:</strong> 01/10/2025</p>
        </div>
    </div>
</body>
</html>
