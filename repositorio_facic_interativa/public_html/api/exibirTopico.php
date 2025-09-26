<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idTopico = $seguranca->antisql($_REQUEST["idTopico"]);

$sql = "SELECT titulo, conteudo FROM topico WHERE idTopico = '$idTopico'";
$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if ($linhas > 0) {
  $dados = mysql_fetch_assoc($result);
  $dados["comentario"] = [];
  
  // COMENTARIOS
  $sql = "SELECT * FROM comentario WHERE idTopico = '$idTopico' ORDER BY idComentario";
  $result = mysql_query($sql);
  $linhas = mysql_num_rows($result);

  if ($linhas > 0) {
    for ($i = 0; $i < $linhas; $i++) {
      $idComentario = mysql_result($result, $i, "idComentario");
      $comentario = mysql_result($result, $i, "comentario");
      $idUsuario = mysql_result($result, $i, "idUsuario");
      $tipo = mysql_result($result, $i, "tipo");

      $nome = $tipo == "aluno" 
              ? getNome("usuario", "idUsuario", $idUsuario) 
              : getNome("professor", "idProfessor", $idUsuario);

      $dados["comentario"][$i] = [
        "idComentario" => $idComentario,
        "comentario" => $comentario,
        "idUsuario" => $idUsuario,
        "tipo" => $tipo,
        "nome" => $nome,
        "respostas" => []
      ];

      // RESPOSTAS
      $sqlSub = "SELECT * FROM subresposta WHERE idComentario = '$idComentario' ORDER BY idSub";
      $resultSub = mysql_query($sqlSub);
      $linhasSub = mysql_num_rows($resultSub);

      if ($linhasSub > 0) {
        for ($j = 0; $j < $linhasSub; $j++) {
          $idUsuario = mysql_result($resultSub, $j, "idUsuario");
          $idResposta = mysql_result($resultSub, $j, "idSub");
          $resposta = mysql_result($resultSub, $j, "resposta");
          $tipo = mysql_result($resultSub, $j, "tipo");
          $nome = $tipo == "aluno" 
              ? getNome("usuario", "idUsuario", $idUsuario) 
              : getNome("professor", "idProfessor", $idUsuario);

          $dados["comentario"][$i]["respostas"][] = [
            "idResposta" => $idResposta,
            "resposta" => $resposta,
            "tipo" => $tipo,
            "nome" => $nome
          ];
        }
      }
    }
  }
  
  echo json_encode($dados);
} else {
  echo json_encode([]);
}

function getNome($tabela, $campo, $id) {
  $query = "SELECT nome FROM $tabela WHERE $campo = '$id'";
  $resultNome = mysql_query($query);
  if(mysql_num_rows($resultNome) > 0) {
      $nome = mysql_result($resultNome, 0, "nome");
      return $nome;
  } else {
      return "Usuário não encontrado.";
  }
}