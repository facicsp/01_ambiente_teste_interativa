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
            if ($_SESSION["tipo"] == "professor") {
                //conteudo do site
                
                
include 'conexao.php';
 
$seguranca = new Seguranca();

$operacao = $seguranca->antisql($_POST["operacao"]);

$id = $seguranca->antisql($_POST["id"]);

if($operacao == 'alterar'){
$Titulo = $seguranca->antisql($_POST["txtTitulo"]);
$Video = $seguranca->antisql($_POST["txtVideo"]);
$Iddisciplina = $seguranca->antisql($_POST["txtIddisciplina"]);
$Idprofessor = $seguranca->antisql($_POST["txtIdprofessor"]);
$sql = "UPDATE video SET titulo = '$Titulo',video = '$Video',iddisciplina = '$Iddisciplina',idprofessor = '$Idprofessor' WHERE idvideo = '$id'";mysql_query($sql);
echo "<script>
alert('Alteração realizada com sucesso!');
window.location = 'cadastrovideo.php';
</script>";}else if($operacao == 'excluir'){
$sql = "DELETE FROM video WHERE idvideo = $id";mysql_query($sql);
echo "<script>
alert('Exclusão realizada com sucesso!');
window.location = 'cadastrovideo.php';
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