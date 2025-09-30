<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$idUsuario = $seguranca->antisql($_REQUEST["id"]);

$result = mysqli_query($conexao, "SELECT * FROM usuario WHERE idUsuario = $idUsuario");
$linhas = mysqli_num_rows($result);

if($linhas > 0) echo json_encode(mysqli_fetch_assoc($result));
else echo -1;

exit;