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
if (isset($_SESSION["usuario"])) {
  if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor"){
//Enderecos que devem ser enviadas as mensagens
include '../conexao.php';
$seguranca = new Seguranca();
$idTurma = $seguranca->antisql($_GET["idTurma"]);


$msg = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
<title>Ambiente Virtual Facic</title>
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
    $msg .= "<p>Há um novo conteúdo disponível no Ambiente Virtual!</p>";
    $msg .= "<p><b> Acesse: </b><a href='http://facicinterativa.com.br/' target='_blank'>facicinterativa.com.br</a></p>";
    $msg .= "</body>
</html>";



$assunto = "Nova Aula";
$conteudo = "Ambiente Virtual Facic - Contato";
$header  = "Content-type: text/html; charset=iso-8859-1\n";
$header .= "From: Ambiente Virtual Facic<contato@facicinterativa.com.br>";



$sql = "SELECT usuario.email from usuario,matricula where matricula.idTurma = '$idTurma' and matricula.idAluno = usuario.idUsuario";
$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if($linhas > 0){
    for($i = 0;$i < $linhas;$i++){
        $email = mysql_result($result, $i, "email");
        $mail = ("$email");
        mail($mail, $assunto, $msg, $header);
        echo "<p>Enviado para $email</p>";
    }
    
    
}else{
    echo "Nenhum aluno encontrado na turma.";
}




echo "
<script>
alert('Todos os alunos foram informados.');
window.location='../cadastroAula.php';
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