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
                
                
include 'LoginRestrito/conexao.php';
 
$seguranca = new Seguranca();
$Categoria = $seguranca->antisql($_POST["txtCategoria"]);
$sql = "INSERT INTO categoria VALUES(null,'$Categoria')";mysqli_query($conexao, $sql);
echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastrocategoria.php';
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