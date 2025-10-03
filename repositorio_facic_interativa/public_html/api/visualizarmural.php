<?php

include 'hasAccess.php';

$seguranca = new Seguranca();
$idNoticia = $seguranca->antisql($_REQUEST["idNoticia"]);

$sql = "SELECT n.*,d.disciplina,p.nome,date_format(n.data,'%d/%m/%Y') as data2 FROM noticia n,disciplina d,professor p WHERE n.idnoticia = '$idNoticia' AND n.iddisciplina = d.idDisciplina AND n.idprofessor = p.idProfessor";
$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if($linhas > 0){
  for($i=0;$i<$linhas;$i++){
    $tipo = mysql_result($result, $i,"tipo");
    $titulo = mysql_result($result, $i,"titulo");
    $texto = mysql_result($result, $i,"texto");
    $data = mysql_result($result, $i,"data2");
    $disciplina = mysql_result($result, $i,"disciplina");
    $professor = mysql_result($result, $i,"nome");
    $status = mysql_result($result, $i,"status");

    echo "<div class=\"mural\">
      <h2>$titulo</h2>
      <div>$texto</div>
    <div>
      <p style='font-size:14px;'><strong>Área: </strong>$disciplina - Postado por: $professor em $data.</p>
      </div>
      <hr>
    </div>";
  }
} else echo json_encode("<p>Nenhum conteúdo disponível</p>");