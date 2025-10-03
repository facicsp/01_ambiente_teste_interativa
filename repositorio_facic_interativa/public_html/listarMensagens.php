<?php
session_start();

function MontarLink($texto)
{
    if (!is_string($texto))
        return $texto;
    
    $texto = htmlentities($texto);
    
    $er = "/((http|https|ftp|ftps):\/\/(www\.|.*?\/)?|www\.)([a-zA-Z0-9]+|_|-)+(\.(([0-9a-zA-Z]|-|_|\/|\?|=|&)+))+/i";
    preg_match_all($er, $texto, $match);
    
    foreach ($match[0] as $link) {
        //coloca o 'http://' caso o link não o possua
        if (stristr($link, "http://") === false && stristr($link, "https://") === false) {
            $link_completo = "http://" . $link;
        } else {
            $link_completo = $link;
        }
        
        $link_len = strlen($link);
        
        //troca "&" por "&", tornando o link válido pela W3C
        $web_link = str_replace("&", "&amp;", $link_completo);
        $texto    = str_ireplace($link, "<a href=\"" . $web_link . "\" target=\"_blank\">" . (($link_len > 60) ? substr($web_link, 0, 25) . "..." . substr($web_link, -15) : $web_link) . "</a>", $texto);
        
    }
    return $texto;
}


if (isset($_SESSION["usuario"])) {
    if ($_SESSION["tipo"] == "aluno" || $_SESSION["tipo"] == "professor") {
        
        include './conexao.php';
        $seguranca = new Seguranca();
        $idEscritor = $seguranca->antisql($_SESSION["id"]);
        $tipoEscritor = $seguranca->antisql($_SESSION["tipo"]);
        $semestre = $seguranca->antisql($_SESSION["semestre"]);

        $tipoGrupo = $_GET["tipo"];
        $idGrupo = false;

        // id usado apenas para conversas com o professor
        $idAluno = '';

        if ($tipoEscritor == "aluno") {
            if ($tipoGrupo == "curso" || $tipoGrupo == "turma") {
                $result = mysql_query("SELECT turma.idTurma, turma.idCurso FROM matricula
                    LEFT JOIN turma ON turma.idTurma = matricula.idTurma
                    WHERE idaluno = '$idEscritor' AND ativo = 'sim' AND semestre = '$semestre'");
    
                if (mysql_num_rows($result) > 0) {
                    $idGrupo = mysql_result($result, 0, ($tipoGrupo == "turma" ? "idTurma" : "idCurso"));
                }
            } else if (isset($_GET["professor"])) {
                $idGrupo = $seguranca->antisql($_GET["professor"]);
                $idAluno = $idEscritor;
            }
        } else if ($tipoEscritor == "professor") {
            if ($tipoGrupo == "professor") {
                $idGrupo = $idEscritor;
                $idAluno = $seguranca->antisql($_GET["aluno"]);
            } else if ($tipoGrupo == "turma") {
                $idGrupo = $seguranca->antisql($_GET["turma"]);
            } else if ($tipoGrupo == "curso") {
                $idGrupo = $seguranca->antisql($_GET["curso"]);
            } else if ($tipoGrupo == "salaprofessores") {
                $idGrupo = -1;
            }
        }


        if (!$idGrupo) exit(false);

        $dados = [];
        $where = "";

        if ($tipoGrupo == "professor") {
            $where = "AND idAluno = $idAluno";
        }

        $result = mysql_query("SELECT mensagem, DATE_FORMAT(data, '%d/%m/%Y às %Hh%i') AS data_formatada, 
            nome, tipoEscritor, idEscritor, anexo FROM mensagens 
            LEFT JOIN usuario ON usuario.idUsuario = mensagens.idEscritor 
            WHERE idGrupo='$idGrupo' AND tipoGrupo='$tipoGrupo' $where");

        $linhas = mysql_num_rows($result);
        if ($linhas > 0) {
            for ($i=0; $i < $linhas; $i++) { 
                $mensagem = mysql_result($result, $i, "mensagem");
                $data = mysql_result($result, $i, "data_formatada");
                $nome = mysql_result($result, $i, "nome");
                $tipoEscritor = mysql_result($result, $i, "tipoEscritor");
                $idEscritor2 = mysql_result($result, $i, "idEscritor");
                $anexo = mysql_result($result, $i, "anexo");

                if ($tipoEscritor == "professor") {
                    $r = mysql_query("SELECT nome FROM professor WHERE idProfessor = '$idEscritor2'");
                    $nome = mysql_result($r, 0, "nome");
                }

                $dados[] = [
                    "data" => $data, 
                    "mensagem" => $mensagem, 
                    "nome" => $idEscritor != $idEscritor2 ? $nome : "EU", 
                    "tipo" => $tipoEscritor,
                    "anexo" => $anexo
                ];
            }
        }

        echo json_encode($dados);
    }
}