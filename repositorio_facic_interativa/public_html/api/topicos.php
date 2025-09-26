<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idDisciplina = $seguranca->antisql($_REQUEST["idDisciplina"]);

$sql = "SELECT idTopico, titulo FROM topico WHERE idDisciplina = '$idDisciplina'";
$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if ($linhas > 0) {
  while($row = mysql_fetch_assoc($result)) {
      $rows[] = $row;
  }
  
  echo json_encode($rows);
} else {
  echo json_encode([]);
}