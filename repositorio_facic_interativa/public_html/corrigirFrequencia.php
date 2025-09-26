<?php
session_start();
if (isset($_SESSION["usuario"])) {
$idFrequencia = $_REQUEST["idFrequencia"];
$frequencia = $_REQUEST["frequencia"];
include 'conexao.php';
if($frequencia == "P"){
    $novaFrequencia = "F";
}else if($frequencia == "F"){
    $novaFrequencia = "P";
}
$sql = "UPDATE frequencia SET frequencia='$novaFrequencia' WHERE idFrequencia = '$idFrequencia'";

if(mysql_query($sql)){
echo "Alterado";
}else{
    echo "Erro";
}
}
?>