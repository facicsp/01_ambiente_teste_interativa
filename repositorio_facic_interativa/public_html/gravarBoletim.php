<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
        <script>
        function carregar(idDisciplina, idTurma){
            document.body.innerHTML += "<form id=\"dynForm\" action=\"notas.php\" method=\"post\"><input type=\"hidden\" name=\"idDisciplina\" value=\""+idDisciplina+"\"'><input type=\"hidden\" name=\"idTurma\" value=\""+idTurma+"\"'></form>";
            document.getElementById("dynForm").submit();
  }
        </script>
    </head>
<body>
<?php
session_start();
if (isset($_SESSION["usuario"])) {
  if ($_SESSION["tipo"] == "professor") {
      
      include 'LoginRestrito/conexao.php';
      $seguranca = new Seguranca();
$idBoletim = $seguranca->antisql($_POST["idBoletim"]);
$idDisciplina = $seguranca->antisql($_POST["idDisciplina"]);
$idTurma = $seguranca->antisql($_POST["idTurma"]);
$bimestre1 = $seguranca->antisql($_POST["txtBimestre1"]);
$bimestre2 = $seguranca->antisql($_POST["txtBimestre2"]);
$exame = $seguranca->antisql($_POST["txtExame"]);
$sub = $seguranca->antisql($_POST["txtSub"]);
$t1 = $seguranca->antisql($_POST["txtT1"]);
$t2 = $seguranca->antisql($_POST["txtT2"]);
//t1='$t1',t2='$t2'

// $bimestre1 = round($bimestre1 / 0.5, 0) * 0.5;
// $bimestre2 = round($bimestre2 / 0.5, 0) * 0.5;
// $exame = round($exame / 0.5, 0) * 0.5;
// $sub = round($sub / 0.5, 0) * 0.5;

$sql = "UPDATE boletim SET bimestre1='$bimestre1',bimestre2='$bimestre2',exame='$exame',sub='$sub' WHERE idBoletim = '$idBoletim'";
//echo $sql;
    mysqli_query($conexao, $sql);

echo "<script>
alert('Gravação realizada com sucesso!');
carregar('$idDisciplina', '$idTurma');
//window.location = 'notas.php';
</script>
";

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