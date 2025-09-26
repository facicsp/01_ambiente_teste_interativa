<?php

//Enderecos que devem ser enviadas as mensagens
$mail = ("leonardoalves.sun@gmail.com");
$mail2 = ("brunoireland@hotmail.com");
// recebendo os dados od formulario
if (isset($_POST['txtNome'])) {
    $nome = ucwords($_POST['txtNome']);
    $fone = $_POST["txtTelefone"];
    $email = $_POST['txtEmail'];
    $mensagem = $_POST['txtMensagem'];
    $msg = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
<title>Cara Velho</title>
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
    $msg .= "<b> Nome:</b> $nome<br>\n";
    $msg .= "<b> Fone:</b> $fone<br>\n";
    $msg .= "<b> E-mail:</b> $email<br>\n";
    $msg .= "<b> Mensagem:</b> $mensagem<br>\n";
    $msg .= "</body>
</html>";
}


$assunto = "Contato Site";
$conteudo = "CaraVelho - Contato";
$header  = "Content-type: text/html; charset=iso-8859-1\n";
$header .= "From: Cara Velho - Contato on-line<bandacaravelho@gmail.com>";

mail($mail, $assunto, $msg, $header);
mail($mail2, $assunto, $msg, $header);


echo "
<script>
alert('Mensagem enviada com sucesso! Em breve entraremos em contato.');
window.location='../index.php';
</script>
";

?>