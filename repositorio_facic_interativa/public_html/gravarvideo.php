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
            if ($_SESSION["tipo"] == "administrador") {
                //conteudo do site
                
                
include 'conexao.php';
 
$seguranca = new Seguranca();
$Titulo = $seguranca->antisql($_POST["txtTitulo"]);
$Video = $seguranca->antisql($_POST["txtVideo"]);
$Iddisciplina = $seguranca->antisql($_POST["txtIddisciplina"]);
$Idprofessor = $seguranca->antisql($_POST["txtIdprofessor"]);
$sql = "INSERT INTO video VALUES(null,'$Titulo','$Video','$Iddisciplina','$Idprofessor')";mysql_query($sql);
echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastrovideo.php';
</script>";} else {
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