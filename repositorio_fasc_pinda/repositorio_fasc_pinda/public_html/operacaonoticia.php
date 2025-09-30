<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
    </head>
    <body>
        <?php
        session_start();
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "administrador") {
                //conteudo do site
                
                
include 'LoginRestrito/conexao.php';
 
$seguranca = new Seguranca();

$operacao = $seguranca->antisql($_POST["operacao"]);

$id = $seguranca->antisql($_POST["id"]);

if($operacao == 'alterar'){
$Tipo = $seguranca->antisql($_POST["txtTipo"]);
$Titulo = $seguranca->antisql($_POST["txtTitulo"]);
$Texto = $_POST["elm1"];
$Data = $seguranca->antisql($_POST["txtData"]);
$Iddisciplina = $seguranca->antisql($_POST["txtIddisciplina"]);
$Status = $seguranca->antisql($_POST["txtStatus"]);
$sql = "UPDATE noticia SET tipo = '$Tipo',titulo = '$Titulo',texto = '$Texto',data = '$Data',iddisciplina = '$Iddisciplina',status = '$Status' WHERE idnoticia = '$id'";mysqli_query($conexao, $sql);
//echo $sql;
echo "<script>
alert('Alteração realizada com sucesso!');
window.location = 'cadastromural.php';
</script>";}else if($operacao == 'excluir'){
$sql = "DELETE FROM noticia WHERE idnoticia = $id";mysqli_query($conexao, $sql);
echo "<script>
alert('Exclusão realizada com sucesso!');
window.location = 'cadastromural.php';
</script>";}} else {
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