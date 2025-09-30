<?php
include "LoginRestrito/conexao.php";
$sql = "DELETE FROM usuario WHERE idUsuario > 5129";
mysqli_query($conexao, $sql);
?>