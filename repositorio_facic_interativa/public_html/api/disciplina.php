<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

if (strlen($idAluno) < 1) exit(json_encode([]));

// SALVA O ACESSO DO ALUNO
date_default_timezone_set('America/Sao_Paulo');
$_data        = date("Y-m-d");
$_horaentrada = date("H:i");
$_horasaida   = date("H:i");
mysqli_query($conexao, "INSERT INTO acesso VALUES (NULL, '$idAluno', '$_data', '$_horaentrada', '$_horasaida')");

// GAMBIARRA - MATRICULA ADAPTADA
$sql = "select disciplina.disciplina,usuario.nome,listadisciplina.idListaDisciplina,listadisciplina.ativo,professor.nome as professor,listadisciplina.idDisciplina
from listadisciplina,usuario,disciplina,professor
WHERE listadisciplina.idAluno = '$idAluno'
AND listadisciplina.idDisciplina = disciplina.idDisciplina
AND listadisciplina.idAluno = usuario.idUsuario
AND disciplina.idProfessor = professor.idProfessor AND semestre = '". SEMESTRE ."'";
            
$result = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($result);
if ($linhas > 0) {
    for ($i = 0; $i < $linhas; $i++) {
        $idDisciplina = mysql_result($result, $i, "idDisciplina");
        $idListaDisciplina = mysql_result($result, $i, "idListaDisciplina");
        $disciplina = mysql_result($result, $i, "disciplina");
        $nome = mysql_result($result, $i, "professor");
        
        $rows[] = [
            "idDisciplina" => $idDisciplina, 
            "disciplina" => $disciplina, 
            "idTurma" => null, 
            "docente" => $nome
        ];
        
    }
}
// FIM GAMBIARRA
            
$result = mysqli_query($conexao, "SELECT idDisciplina, disciplina, idTurma, professor.nome AS docente FROM disciplina, professor 
                        WHERE idturma IN(SELECT idturma FROM matricula WHERE idaluno = '$idAluno') 
                        AND disciplina.idProfessor = professor.idProfessor AND semestre = '" . SEMESTRE . "'");

$linhas = mysqli_num_rows($result);

if ($linhas > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    echo json_encode($rows);
} else {
    if (sizeof($rows) == 0) echo json_encode([]);
    else echo json_encode($rows);
}

exit;