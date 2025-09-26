<?php

session_start();

if (!isset($_SESSION["idAtividade"]) || $_SESSION["idAtividade"] == "") exit();

$idUsuario = $_SESSION["id"];
$idAtividade = $_SESSION["idAtividade"];
$arquivo = $_SESSION["atividadeArquivo"];

unset($_SESSION["atividadeArquivo"]);
unset($_SESSION["idAtividade"]);

include '../conexao.php';

$result = mysql_query("SELECT nome, ra, email FROM usuario WHERE idUsuario = '$idUsuario'");
$ra     = mysql_result($result, 0, "ra");
$nome   = mysql_result($result, 0, "nome");
$email  = mysql_result($result, 0, "email");

$result  = mysql_query("SELECT * FROM atividade WHERE idAtividade = '$idAtividade'");
$titulo   = mysql_result($result, 0, "titulo");
$idAula   = mysql_result($result, 0, "idAula");
$idAluno  = mysql_result($result, 0, "idAluno");
$data     = mysql_result($result, 0, "data");
$hora     = mysql_result($result, 0, "hora");
$idDisciplina = mysql_result($result, 0, "idDisciplina");

$result = mysql_query("SELECT disciplina FROM disciplina WHERE idDisciplina = '$idDisciplina'");
$disciplina = mysql_result($result, 0, "disciplina");

mysql_query("INSERT INTO protocolo VALUES(NULL, '$idAtividade', DEFAULT)");
$result = mysql_query("SELECT * FROM protocolo WHERE idAtividade = '$idAtividade'");
$protocolo = mysql_result($result, 0, "idProtocolo");

$msg = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
<title>Ambiente Virtual</title>
<style type=\"text/css\">
<!--
body{display: flex;
    flex-direction: column;
    align-items: center;}
    .container{border: 1px solid;
    border-radius: 5px;
    padding: 20px;}
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
    <div class='container'>
  <h1>Protocolo de envio de atividade</h1>
  <p>Número de protocolo: $protocolo</p>
  <p>Data de envio: $data às $hora</p>
  <p>Atividade: $titulo</p>
  <p>Arquivo: $arquivo</p>
  <p>Aluno: $nome #$ra</p>
  <p>Disciplina: $disciplina #$idDisciplina</p>
  </div>
</body>
</html>";

$assunto = "Protocolo de envio de atividade";
$conteudo = "FACIC INTERATIVA";
$header  = "Content-type: text/html; charset=iso-8859-1\n";
$header .= "From: FACIC INTERATIVA<sistemafacic@ava24horas.com>";

$mail = ("$email");
mail($mail, $assunto, $msg, $header);

echo "$msg <a href='../cadastroAtividade.php' style='text-decoration: none;
    color: #FFF;
    background-color: #3cadd4;
    padding: 10px 20px;
    border-radius: 5px;
    margin-top: 20px;
    text-transform: uppercase;'>Voltar</a>";
  
  
  

// echo "<script>window.location = '../cadastroAtividade.php';</script>";