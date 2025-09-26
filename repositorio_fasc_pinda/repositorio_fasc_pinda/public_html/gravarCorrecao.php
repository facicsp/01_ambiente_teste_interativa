<?php

session_start();

if (isset($_SESSION["usuario"])) {

    $idAtividade = $_POST["idAtividade"];
    $arquivo = $_FILES["arquivo"];

    if ($arquivo["size"] > 0) {
        $extensao = array_reverse(explode(".", $arquivo["name"]))[0];
        $caminho  = "correcao/" . md5(date("Y-m-d H:m:i")) . ".$extensao";

        if($extensao == "pdf" || $extensao == "doc" || $extensao == "docx" || $extensao == "xls" || $extensao == "xlsx" || $extensao == "ppt" || $extensao == "pptx" || $extensao == "jpg" || $extensao == "txt" || $extensao == "rar" || $extensao == "zip"){
            move_uploaded_file($arquivo['tmp_name'], $caminho);
        
            exit("ok");
        }
    }
}

exit(false);