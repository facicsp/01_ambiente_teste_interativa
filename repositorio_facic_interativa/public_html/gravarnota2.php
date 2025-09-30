<?php

session_start();

// ALTER TABLE `atividade` ADD `arquivo_correcao` VARCHAR(500) NOT NULL AFTER `retorno`;

if (isset($_SESSION["usuario"])) {

    $idAtividade = $_POST["idAtividade"];
    $nota        = $_POST["nota"];
    $retorno     = $_POST["retorno"];
    $status      = $_POST["status"];
    $data        = date("Y-m-d");
    $caminho     = "";

    if (isset($_FILES["arquivo"])) {
        $extensao = array_reverse(explode(".", $_FILES["arquivo"]["name"]))[0];
        $caminho  = "correcao/" . md5(date("Y-m-d H:m:i")) . ".$extensao";

        if($extensao == "pdf" || $extensao == "doc" || $extensao == "docx" || $extensao == "xls" || $extensao == "xlsx" || $extensao == "ppt" || $extensao == "pptx" || $extensao == "jpg" || $extensao == "txt" || $extensao == "rar" || $extensao == "zip"){
            move_uploaded_file($_FILES["arquivo"]['tmp_name'], $caminho);
        } else {
            exit("Erro");
        }
    }
    
    include 'LoginRestrito/conexao.php';
    
    if($status == "comdata") {
        $sql = "UPDATE atividade SET arquivo_correcao='$caminho', nota='$nota', retorno='$retorno' WHERE idAtividade = '$idAtividade'";    
    } else if($status == "semdata") {
        $sql = "UPDATE atividade SET arquivo_correcao='$caminho', nota='$nota', retorno='$retorno', data='$data' WHERE idAtividade = '$idAtividade'";
    }

    if(mysqli_query($conexao, $sql)){
        echo "Alterado";
    }else{
        echo "Erro";
    }
}

?>