<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);
$idDisciplina = $seguranca->antisql($_REQUEST["idDisciplina"]);

$sql = "SELECT 
          aula.idAula, aula.descricao,
          DATE_FORMAT(dataAula,'%d/%m/%Y') AS dataAula, 
          DATE_FORMAT(dataAtividade,'%d/%m/%Y') AS dataAtividade 
        FROM aula 
        WHERE iddisciplina = '$idDisciplina' 
        ORDER BY aula.dataAula";

$result = mysqli_query($conexao, $sql);
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