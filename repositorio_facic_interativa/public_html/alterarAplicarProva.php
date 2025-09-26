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
    $data         = $seguranca->antisql($_POST["txtData"]);
    $idProfessor  = $_SESSION['id'];

$sql = "UPDATE aplicarprova SET titulo = '$titulo', `fechamento` = '$data' WHERE idAplicarProva = '$idAplicar' AND idProfessor = '$idProfessor'";
// exit($sql);
mysql_query($sql);
echo "<script>alert('Gravação realizada com sucesso!'); window.location = 'aplicarProva.php';</script>";
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