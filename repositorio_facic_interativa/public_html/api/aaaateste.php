<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

if (strlen($idAluno) < 1) exit(json_encode([]));

// GAMBIARRA - MATRICULA ADAPTADA
$sql = "select disciplina.disciplina,usuario.nome,listadisciplina.idListaDisciplina,listadisciplina.ativo,professor.nome as professor,listadisciplina.idDisciplina
from listadisciplina,usuario,disciplina,professor
WHERE listadisciplina.idAluno = '$idAluno'
AND listadisciplina.idDisciplina = disciplina.idDisciplina
AND listadisciplina.idAluno = usuario.idUsuario
AND disciplina.idProfessor = professor.idProfessor AND semestre = '". SEMESTRE ."'";
            
$result = mysql_query($sql);
$linhas = mysql_num_rows($result);
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
            
$result = mysql_query("SELECT idDisciplina, disciplina, idTurma, professor.nome AS docente FROM disciplina, professor 
                        WHERE idturma IN(SELECT idturma FROM matricula WHERE idaluno = '$idAluno') 
                        AND disciplina.idProfessor = professor.idProfessor AND semestre = '" . SEMESTRE . "'");

$linhas = mysql_num_rows($result);

if ($linhas > 0) {
    while($row = mysql_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    echo json_encode($rows);
} else {
    if (sizeof($rows) == 0) echo json_encode([]);
    else echo json_encode($rows);
}

exit;



/*
<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

if (strlen($idAluno) < 1) exit(json_encode([]));
            
$result = mysql_query("SELECT idDisciplina, disciplina, idTurma, professor.nome AS docente FROM disciplina, professor 
                        WHERE idturma IN(SELECT idturma FROM matricula WHERE idaluno = '$idAluno') 
                        AND disciplina.idProfessor = professor.idProfessor AND semestre = '" . SEMESTRE . "'");

$linhas = mysql_num_rows($result);

if ($linhas > 0) {
    while($row = mysql_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    echo json_encode($rows);
} else {
    echo json_encode([]);
}

exit; */
