<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
    </head>
<body>
<?php
if (isset($_SESSION["email"])) {
  
//Enderecos que devem ser enviadas as mensagens
$email = $_SESSION["email"];
$idUsuario = $_SESSION["idUsuario"];
$senha = "";
for($i=0;$i<10;$i++){
$senha .="".rand(0, 100);

}
include '../conexao.php';
$sql = "UPDATE usuario SET senha2 = '$senha' WHERE email='$email'";
mysqli_query($conexao, $sql);

$msg = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
<title>Ambiente Virtual Fasc</title>
<style type=\"text/css\">
<!--
.style1 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style2 {font-size: 10px}
.style4 {font-size: 12px}
.style5 {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 12px;
        font-weight: bold;
}
.style6 {font-size: 14px}
-->
</style>
</head>

<body>
";
    $msg .= "<p>Olá</p>";
    $msg .= "<p>Houve uma solicitação para redefinir sua senha no sistema!</p>";
    $msg .= "<p><b> Acesse para redefinir: </b><a href='http://fascinterativa.com.br/redefinirSenha.php?email=$idUsuario&codigo=$senha' target='_blank'>fascinterativa.com.br</a></p>";
    $msg .= "<p>Caso contrário, desconsidere esta mensagem.</p>";
    $msg .= "</body>
</html>";



$assunto = "Redefinir Senha";
$conteudo = "Ambiente Virtual - Contato";
$header  = "Content-type: text/html; charset=iso-8859-1\n";
$header .= "From: Ambiente Virtual Fasc<contato@fascinterativa.com.br>";



        $mail = ("$email");
        mail($mail, $assunto, $msg, $header);
        

echo "
<script>
alert('Verifique seu email e siga as instruções.');
window.location='../login.html';
</script>
";
}else{
      echo "Acesso negado!;";
      echo "<a href='login.html'>Faça o login!</a>";
  }  

?>