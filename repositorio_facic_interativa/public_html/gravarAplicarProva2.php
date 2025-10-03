<?php
session_start();

if (isset($_SESSION["usuario"])) {

  if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor"){
    include 'conexao.php';

    $seguranca    = new Seguranca();
    $titulo       = $seguranca->antisql($_POST["txtDescricao"]);
    $titulo       = str_replace("'", "`", $titulo);
    $idTurma      = $seguranca->antisql($_POST["txtTurma"]);
    $idDisciplina = $seguranca->antisql($_POST["txtDisciplina"]);
    $idProva      = $seguranca->antisql($_POST["txtProva"]);
    $tipo         = $seguranca->antisql($_POST["txtTipo"]);
    $idProfessor  = $_SESSION['id'];

    if ($tipo > 0) { // p1,p2,av1,av2,sub,exame
      $fechamento = $seguranca->antisql($_POST["txtDataAtividade"]);
      $data       = $seguranca->antisql($_POST["txtData"]);
      $sql        = "INSERT INTO aplicarprova VALUES (NULL, '$titulo', '$idProva', '$data', '$fechamento', '$idDisciplina', '$idTurma', '$tipo', '$idProfessor')";
    } else { // frequência
      $idAplicar = $seguranca->antisql($_POST["txtIdAplicar"]);
      $sql       = "UPDATE aplicarprova SET titulo='$titulo', idProva='$idProva', idProfessor='$idProfessor' WHERE idAplicarProva='$idAplicar'";
    }

    mysql_query($sql);

    echo "<script>alert('Gravação realizada com sucesso!'); window.location = 'aplicarProva2.php';</script>";

}else{

      echo "Acesso negado!;";

      echo "<a href='login.html'>Faça o login!</a>";

  }  

} else {

    echo "<script>"

    . "alert('É necessário fazer o login!');"

    . "window.location='login.html';"

    . "</script>";

}

?>