<?php
    
include 'hasAccess.php';

// exit(json_encode($_FILES));

$seguranca = new Seguranca();
$idAula = $seguranca->antisql($_REQUEST["idAula"]);
$idAluno = $seguranca->antisql($_REQUEST["idAluno"]);
$titulo = $seguranca->antisql($_REQUEST["txtTitulo"]);
$data = $seguranca->antisql($_REQUEST["data"]);
$hora = $seguranca->antisql($_REQUEST["hora"]);
$obs = $seguranca->antisql($_REQUEST["txtObservacao"]);

$result = mysqli_query($conexao, "SELECT idAtividade FROM atividade WHERE idAula='$idAula'");
$linhas = mysqli_num_rows($result);

if($linhas > 0) {
  $linhas++;
  $nomeArquivo = "Atividade$idAula-$idAluno"."_$linhas";
} else {
  $nomeArquivo = "Atividade$idAula-$idAluno"."_1";
}

$uploadpast = "atividades";

$result = mysqli_query($conexao, "SELECT idDisciplina FROM aula WHERE idAula = '$idAula'");
$idDisciplina = mysql_result($result, 0, 'idDisciplina');

if($_FILES['txtArquivo']['size'] == 0) {
  mysqli_query($conexao, "INSERT INTO atividade VALUES(null,'$titulo','','$idAula','$idAluno','$data','$hora','$obs','$idDisciplina','0','')");
  json_encode("INSERT INTO atividade VALUES(null,'$titulo','','$idAula','$idAluno','$data','$hora','$obs','$idDisciplina','0','')");
} else {
  if($_FILES['txtArquivo']['size'] <= 10500000) {
    $uploadarq = $uploadpast . $_FILES['txtArquivo']['name'];
    $arquivo = explode(".", $uploadarq);
    $extensao = $arquivo[sizeof($arquivo) - 1];

    if($extensao == "pdf" || $extensao == "doc" || $extensao == "docx" || $extensao == "xls" || $extensao == "xlsx" || $extensao == "ppt" || $extensao == "pptx" || $extensao == "jpg" || $extensao == "txt" || $extensao == "rar" || $extensao == "zip") {
      $uploadarq = "$uploadpast/$nomeArquivo.$extensao";

      // exit(json_encode($uploadarq));

      if (!move_uploaded_file($_FILES['txtArquivo']['tmp_name'], "../$uploadarq")) exit(json_encode("Ops! Houve algum erro. ".$_FILES["txtArquivo"]["error"]));

      mysqli_query($conexao, "INSERT INTO atividade VALUES(null,'$titulo','$uploadarq','$idAula','$idAluno','$data','$hora','$obs','$idDisciplina','0','')");
      echo json_encode(true);
      // echo json_encode("INSERT INTO atividade VALUES(null,'$titulo','$uploadarq','$idAula','$idAluno','$data','$hora','$obs','$idDisciplina','0','')");
    } else{
      echo json_encode("Extensão '$extensao' inválida! O arquivo deve ter algum dos seguintes formatos: Pdf,Doc,Docx,Xls,Xlsx,Ppt,Pptx,Jpg,Txt,Rar,Zip.");
    }
  } else {
    echo json_encode("O arquivo enviado ultrapassa o limite! Reduza o tamanho.");
  }
}