<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

if (strlen($idAluno) < 1) exit(json_encode([]));
            
$result = mysqli_query($conexao, "SELECT idDisciplina FROM disciplina 
                        WHERE idturma IN(SELECT idturma FROM matricula WHERE idaluno = '$idAluno') AND semestre = '" . SEMESTRE . "'");

$linhas = mysqli_num_rows($result);

if ($linhas > 0) {
    $disciplinas = '0';

    while($row = mysqli_fetch_assoc($result)) {
        $disciplinas .= ',' . $row['idDisciplina'];
    }

} else exit(json_encode([]));

$result = mysqli_query($conexao, "SELECT idnoticia, titulo FROM noticia 
                        WHERE idDisciplina IN ($disciplinas) ORDER BY RAND() LIMIT 3");

$linhas = mysqli_num_rows($result);

if ($linhas > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    echo json_encode($rows);
} else {
    echo json_encode([]);
}

exit;