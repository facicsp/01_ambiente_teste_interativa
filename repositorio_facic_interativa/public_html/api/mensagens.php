<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

$sql = "SELECT contato.*,usuario.nome,professor.nome AS professor FROM contato,usuario,professor WHERE idAluno = '$idAluno' AND contato.idAluno = usuario.idUsuario AND contato.idProfessor = professor.idprofessor ORDER BY idcontato DESC";
$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if ($linhas > 0) {
  while($row = mysql_fetch_assoc($result)) {
    $idContato = $row["idContato"];
    $consultaStatus = "select idMensagem from mensagem where idContato = '$idContato' and tipo <> 'aluno' and status = 'nao'";
    $resultadoStatus = mysql_query($consultaStatus);
    $linhasStatus = mysql_num_rows($resultadoStatus);

    $row['naoLidas'] = $linhasStatus;
    $rows[] = $row;
  }
  
  echo json_encode($rows);
} else {
  echo json_encode([]);
}