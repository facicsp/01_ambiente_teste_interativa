<?php
include "conexao.php";
$sql = "DELETE FROM usuario WHERE idUsuario > 5129";
mysql_query($sql);
?>