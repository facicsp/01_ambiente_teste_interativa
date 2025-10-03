<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/cadastro.css">
</head>

<body>
    <?php
    if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "administrador") {
            //conteudo do site
            include "topo.php";


            include 'conexao.php';
            $ra = $_POST["txtRa"];
            $endereco = $_POST["txtEndereco"];
            $bairro = $_POST["txtBairro"];
            $cidade = $_POST["txtCidade"];
            $estado = $_POST["txtEstado"];
            $nascimento = $_POST["txtNascimento"];
            $telefone = $_POST["txtTelefone"];
            $celular = $_POST["txtCelular"];
            $email = $_POST["txtEmail"];
            $senha = $_POST["txtSenha"];
            $cep = $_POST["txtCep"];
            $obs = $_POST["txtObservacoes"];
            $tipo = $_POST["txtTipo"];
            $idTurma = $_POST["txtTurma"];
            $adaptado = $_POST["txtAdaptado"];

            
            $nomeCivil = $_POST["txtNome"];
            $nomeSocial = $_POST["txtNomeSocial"];
            if (trim($nomeSocial) !== "") {
                $temp = $nomeCivil;
                $nomeCivil = $nomeSocial;
                $nomeSocial = $temp;
            }

            $sql = "INSERT INTO usuario VALUES(null,'$nomeCivil','$endereco','$bairro','$cidade','$estado','$cep','$nascimento','$telefone','$celular','$email','" . md5($senha) . "','$tipo','$obs','$ra','2020-01-01','$adaptado','0', '', '$nomeSocial')";

            // echo $sql;
            // exit;
    
            //echo "<script>console.log(`$sql`)</script>";
    
            mysql_query($sql);

            if ($idTurma > 0) {
                $sql = "SELECT idUsuario FROM usuario WHERE nome = '$nomeCivil' AND email = '$email'";
                $result = mysql_query($sql);
                $idUsuario = mysql_result($result, 0, "idUsuario");
                date_default_timezone_set("Brazil/East");
                $data = date("Y-m-d");
                $sql = "INSERT INTO matricula VALUES(null,'$idUsuario','$idTurma','$data')";
                mysql_query($sql);


            }



            echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastroUsuario.php';
</script>
";
        } else {
            echo "Acesso negado!;";
            echo "<a href='login.html'>Faça o login!</a>";
        }
    } else {
        echo "<script>"
            . "alert('É necessário fazer o login!');"
            . "window.location='login.html';"
            . "</script>";
    }
    ?>