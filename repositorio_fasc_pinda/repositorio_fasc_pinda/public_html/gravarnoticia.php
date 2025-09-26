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
                
                
include 'conexao.php';
 
$seguranca = new Seguranca();
// $Tipo = $seguranca->antisql($_POST["txtTipo"]);
if($_SESSION["tipo"]=="professor") {
 $tipo = 'Mural';
}else if($_SESSION["tipo"]== "administrador"){
 $tipo = 'Aviso';
}
$Titulo = $seguranca->antisql($_POST["txtTitulo"]);
$Texto = $_POST["elm1"];
$Data = $seguranca->antisql($_POST["txtData"]);
$Iddisciplina = $seguranca->antisql($_POST["txtDisciplina"]);
if($_SESSION["tipo"] == "professor"){
$Idprofessor = $_SESSION["id"];
}else if ($_SESSION["tipo"] == "administrador"){
    $Idprofessor = 0;
}
$Status = $seguranca->antisql($_POST["txtStatus"]);
$sql = "INSERT INTO noticia VALUES(null,'$tipo','$Titulo','$Texto','$Data','$Iddisciplina','$Idprofessor','$Status')";
//echo $sql;
mysql_query($sql);
echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastromural.php';
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