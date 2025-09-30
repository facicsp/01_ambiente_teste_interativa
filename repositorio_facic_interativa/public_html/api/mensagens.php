<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);

$sql = "SELECT contato.*,usuario.nome,professor.nome AS professor FROM contato,usuario,professor WHERE idAluno = '$idAluno' AND contato.idAluno = usuario.idUsuario AND contato.idProfessor = professor.idprofessor ORDER BY idcontato DESC";
$result = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($result);

if ($linhas > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    $idContato = $row["idContato"];
    $consultaStatus = "select idMensagem from mensagem where idContato = '$idContato' and tipo <> 'aluno' and status = 'nao'";
    $resultadoStatus = mysqli_query($conexao, $consultaStatus);
    $linhasStatus = mysqli_num_rows($resultadoStatus);

    $row['naoLidas'] = $linhasStatus;
    $rows[] = $row;
  }
  
  echo json_encode($rows);
} else {
  echo json_encode([]);
}