<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$email = $seguranca->antisql($_POST["email"]);
$codigo = $seguranca->antisql($_POST["codigo"]);
$senha = md5($seguranca->antisql($_POST["senha"]));

if (strlen($codigo) < 8) exit(json_encode(false));

$result = mysql_query("SELECT idUsuario FROM usuario WHERE email='$email' AND recuperar_senha = '$codigo' AND tipo='aluno'");

if(mysql_num_rows($result) > 0) {
    $idUsuario = mysql_result($result, 0, 'idUsuario'); 
    mysql_query("UPDATE usuario SET senha='$senha', recuperar_senha='' WHERE email='$email' AND idUsuario = '$idUsuario'");
    echo json_encode(true);
} else {
    echo json_encode(false);
}

?>