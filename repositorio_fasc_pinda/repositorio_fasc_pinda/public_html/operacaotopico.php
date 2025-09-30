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
                
                
include 'LoginRestrito/conexao.php';
 
$seguranca = new Seguranca();

$operacao = $seguranca->antisql($_POST["operacao"]);

$id = $seguranca->antisql($_POST["id"]);

if($operacao == 'alterar'){
$Titulo = $seguranca->antisql($_POST["txtTitulo"]);
$Conteudo = $seguranca->antisql($_POST["txtConteudo"]);
$Status = $seguranca->antisql($_POST["txtStatus"]);
$IdDisciplina = $seguranca->antisql($_POST["txtIdDisciplina"]);
$sql = "UPDATE topico SET titulo = '$Titulo',conteudo = '$Conteudo',status = '$Status',idDisciplina = '$IdDisciplina' WHERE idtopico = '$id'";mysqli_query($conexao, $sql);
echo "<script>
alert('Alteração realizada com sucesso!');
window.location = 'dadosforum.php';
</script>";
    
}else if($operacao == 'excluir'){
    
mysqli_query($conexao, "DELETE FROM comentario WHERE idTopico = $id");
mysqli_query($conexao, "DELETE FROM topico WHERE idtopico = $id");

echo "<script>
alert('Exclusão realizada com sucesso!');
window.location = 'dadosforum.php';
</script>";
    
}
                
            } else {
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