<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$idUsuario = $seguranca->antisql($_REQUEST["id"]);

$result = mysql_query("SELECT * FROM usuario WHERE idUsuario = $idUsuario");
$linhas = mysql_num_rows($result);

if($linhas > 0) echo json_encode(mysql_fetch_assoc($result));
else echo -1;

exit;