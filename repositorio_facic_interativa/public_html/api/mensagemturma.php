<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

if (strlen($idAluno) < 1) exit(json_encode([]));
            
$result = mysql_query("SELECT idturma FROM disciplina 
                        WHERE idturma IN(SELECT idturma FROM matricula WHERE idaluno = '$idAluno') AND semestre = '" . SEMESTRE . "'");

$linhas = mysql_num_rows($result);

if ($linhas > 0) $idTurma = mysql_result($result, 0, 'idturma');
else exit(json_encode([]));
            
$result = mysql_query("SELECT mensagemturma.*, date_format(data,'%d/%m/%Y') AS datamensagem, professor.nome AS docente FROM mensagemturma, professor 
                        WHERE idturma ='$idTurma' AND mensagemturma.idprofessor = professor.idprofessor 
                        ORDER BY idMensagemTurma DESC");


$linhas = mysql_num_rows($result);

if ($linhas > 0) {
    while($row = mysql_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    echo json_encode($rows);
} else {
    echo json_encode([]);
}

exit;