<?php
session_start();
?>
<?php
if (isset($_SESSION["usuario"])) {
  if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor"){

    include 'conexao.php';

    $seguranca    = new Seguranca();
    $idAplicar    = $seguranca->antisql($_POST["id"]);
    $titulo       = $seguranca->antisql($_POST["txtTitulo"]);
    $titulo       = str_replace("'", "`", $titulo);
    $abertura     = $seguranca->antisql($_POST["txtAbertura"]);
    $fechamento   = $seguranca->antisql($_POST["txtData"]);
    $bimestre     = $seguranca->antisql($_POST["txtContabiliza"]);
    //$bimestre     = $seguranca->antisql($_POST["txtTipo"]);
    $idProfessor  = $_SESSION['id'];

    $sql = "UPDATE aplicarprova SET titulo = '$titulo', bimestre = '$bimestre', abertura = '$abertura', fechamento = '$fechamento' WHERE idAplicarProva = '$idAplicar' AND idProfessor = '$idProfessor'";
    mysql_query($sql);
    echo "<script>console.log(\"$sql\");alert('Gravação realizada com sucesso!'); window.location = 'aplicarProva2.php';</script>";
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