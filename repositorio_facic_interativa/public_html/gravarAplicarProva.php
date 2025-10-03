<?php
session_start();
?>
<?php
if (isset($_SESSION["usuario"])) {
  if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor"){

    include 'conexao.php';

    $seguranca    = new Seguranca();
    $titulo       = $seguranca->antisql($_POST["txtDescricao"]);
    $titulo       = str_replace("'", "`", $titulo);
    $idTurma      = $seguranca->antisql($_POST["txtTurma"]);
    $idDisciplina = $seguranca->antisql($_POST["txtDisciplina"]);
    $idProva      = $seguranca->antisql($_POST["txtProva"]);
    $idProfessor  = $_SESSION['id'];

    if ($_POST["txtAvaliativo"] == 1) {  
        $bimestre = $seguranca->antisql($_POST["txtBimestre"]);
        $fechamento   = $seguranca->antisql($_POST["txtDataAtividade"]);

        if ($bimestre != 1 && $bimestre != 2) {
            echo "<p> Ops! Houve algum erro. </p>";
            exit;
        }

        $sql = "INSERT INTO aplicarprova VALUES(NULL, '$titulo', '$idProva', DEFAULT, '$fechamento', '$idDisciplina', '$idTurma', '$bimestre', '$idProfessor')";
    } else {
        $idAplicar = $seguranca->antisql($_POST["txtIdAplicar"]);

        $sql = "UPDATE aplicarprova SET titulo='$titulo', idProva='$idProva', idProfessor='$idProfessor'  WHERE idAplicarProva='$idAplicar'";
    }

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