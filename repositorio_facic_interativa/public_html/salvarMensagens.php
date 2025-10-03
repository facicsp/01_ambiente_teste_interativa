<?php
session_start();

if (isset($_SESSION["usuario"])) {
    if ($_SESSION["tipo"] == "aluno" || $_SESSION["tipo"] == "professor") {
        
        include './conexao.php';
        $seguranca = new Seguranca();

        $tipoEscritor = $seguranca->antisql($_SESSION["tipo"]);
        $idEscritor = $seguranca->antisql($_SESSION["id"]);
        $semestre = $seguranca->antisql($_SESSION["semestre"]);

        $tipoGrupo = $seguranca->antisql($_POST["tipo"]);

        $mensagem = $seguranca->antisql($_POST["mensagem"]);

        $idGrupo = false;
        $anexo = 'NULL';

        // id usado apenas para conversas com o professor
        $idAluno = 'NULL';

        if ($tipoEscritor == "aluno") {
            if ($tipoGrupo == "curso" || $tipoGrupo == "turma") {
                $result = mysql_query("SELECT turma.idTurma, turma.idCurso FROM matricula
                    LEFT JOIN turma ON turma.idTurma = matricula.idTurma
                    WHERE idaluno = '$idEscritor' AND ativo = 'sim' AND semestre = '$semestre'");
    
                if (mysql_num_rows($result) > 0) {
                    $idGrupo = mysql_result($result, 0, ($tipoGrupo == "turma" ? "idTurma" : "idCurso"));
                }
            } else if (isset($_POST["professor"])) {
                $idGrupo = $seguranca->antisql($_POST["professor"]);
                $idAluno = $idEscritor;
            }
        } else if ($tipoEscritor == "professor") {
            if ($tipoGrupo == "professor") {
                $idGrupo = $idEscritor;
                $idAluno = $seguranca->antisql($_POST["aluno"]);
            } else if ($tipoGrupo == "turma") {
                $idGrupo = $seguranca->antisql($_POST["turma"]);
            } else if ($tipoGrupo == "curso") {
                $idGrupo = $seguranca->antisql($_POST["curso"]);
            } else if ($tipoGrupo == "salaprofessores") {
                $idGrupo = -1;
            }
        }

        if (!$idGrupo) exit('false');





$erro = false;
if (isset($_FILES["anexo"])) {
    $_UP['pasta'] = './mensagens/anexos/';
    $_UP['tamanho'] = 1024 * 1024 * 10;
    $_UP['extensoes'] = array('pdf', 'png', 'jpg', 'doc', 'docx');

    // Array com os tipos de erros de upload do PHP
    $_UP['erros'][0] = 'Não houve erro';
    $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
    $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especificado';
    $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
    $_UP['erros'][6] = 'Faltando uma pasta temporária';
    $_UP['erros'][7] = 'Falha ao gravar arquivo no disco';
    $_UP['erros'][8] = 'Uma extensão PHP interrompeu o upload do arquivo';

    // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
    if ($_FILES['anexo']['error'] != 0) {
        $erro = "Não foi possível fazer o upload, erro: " . $_FILES['anexo']['error'];
        //$_UP['erros'][$_FILES['anexo']['error']];
    } else {
        // Faz a verificação da extensão do arquivo
        $extensao = strtolower(end(explode('.', $_FILES['anexo']['name'])));
        if (array_search($extensao, $_UP['extensoes']) === false) {
            $erro = "Por favor, envie arquivos com as seguintes extensões: " . join($_UP['extensoes'], ", ");
        }
        // Faz a verificação do tamanho do arquivo
        else if ($_UP['tamanho'] < $_FILES['anexo']['size']) {
            $erro = "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
        }

        // O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
        else {
            // Cria um nome baseado no UNIX TIMESTAMP atual
            $caminho = $_UP['pasta'] . md5($_FILES['anexo']['tmp_name']) . time() . "." . $extensao;

            // Depois verifica se é possível mover o arquivo para a pasta escolhida
            if (move_uploaded_file($_FILES['anexo']['tmp_name'], $caminho)) {
                $anexo = "'$caminho'";
            } else {
                // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                $erro = "Não foi possível enviar o arquivo, tente novamente";
            }
        }
    }
}





        if ($erro == false) {
            mysql_query("INSERT INTO mensagens VALUES (NULL, '$idEscritor', '$tipoEscritor', '$idGrupo', '$tipoGrupo', '$mensagem', DEFAULT, $idAluno, $anexo)");
        }

        echo json_encode([
            "mensagem" => $mensagem,
            "erro" => $erro,
            "anexo" => $anexo
        ]);
    }
}