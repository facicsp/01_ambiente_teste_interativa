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
        if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "aluno") {
            //conteudo do site
            include "topo.php";

            include 'conexao.php';
            $seguranca = new Seguranca();
            $operacao = $seguranca->antisql($_POST["operacao"]);
            $id = $seguranca->antisql($_POST["id"]);

            if ($operacao == 'alterar') {
                $ra = $seguranca->antisql($_POST["txtRa"]);
                $endereco = $seguranca->antisql($_POST["txtEndereco"]);
                $bairro = $seguranca->antisql($_POST["txtBairro"]);
                $cidade = $seguranca->antisql($_POST["txtCidade"]);
                $estado = $seguranca->antisql($_POST["txtEstado"]);
                $cep = $seguranca->antisql($_POST["txtCep"]);
                $nascimento = $seguranca->antisql($_POST["txtNascimento"]);
                $telefone = $seguranca->antisql($_POST["txtTelefone"]);
                $celular = $seguranca->antisql($_POST["txtCelular"]);
                $email = $seguranca->antisql($_POST["txtEmail"]);
                $senha = $seguranca->antisql($_POST["txtSenha"]);
                $observacoes = $seguranca->antisql($_POST["txtObservacoes"]);

                if (isset($_POST["txtAdaptado"])) {
                    $adaptado = $seguranca->antisql($_POST["txtAdaptado"]);
                }
                
                $nomeCivil = $seguranca->antisql($_POST["txtNome"]);
                $nomeSocial = $seguranca->antisql($_POST["txtNomeSocial"]);

                // inverte para salvar no banco
                if (trim($nomeSocial) !== "") {
                    $temp = $nomeCivil;
                    $nomeCivil = $nomeSocial;
                    $nomeSocial = $temp;
                }

                $alteraNome = "nome='$nomeCivil', nomesocial='$nomeSocial',";

                if ($_SESSION["tipo"] == "aluno") {
                    $alteraNome = "";
                    $adaptado = "";
                } else {
                    $adaptado = ",adaptado = '$adaptado' ";
                }

                if ($senha == "") {
                    if ($_SESSION["tipo"] == "aluno") {
                        $sql = "UPDATE usuario SET $alteraNome endereco='$endereco',bairro='$bairro',cidade='$cidade',estado='$estado',cep='$cep',nascimento='$nascimento',telefone='$telefone',celular='$celular', email='$email',observacoes='$observacoes' $adaptado WHERE idUsuario = $id";
                    } else {
                        $sql = "UPDATE usuario SET ra='$ra',$alteraNome endereco='$endereco',bairro='$bairro',cidade='$cidade',estado='$estado',cep='$cep',nascimento='$nascimento',telefone='$telefone',celular='$celular', email='$email',observacoes='$observacoes' $adaptado WHERE idUsuario = $id";
                    }
                } else {
                    if ($_SESSION["tipo"] == "aluno") {
                        $sql = "UPDATE usuario SET $alteraNome endereco='$endereco',bairro='$bairro',cidade='$cidade',estado='$estado',cep='$cep',nascimento='$nascimento',telefone='$telefone',celular='$celular', email='$email',observacoes='$observacoes',senha=md5('$senha') $adaptado WHERE idUsuario = $id";
                    } else {
                        $sql = "UPDATE usuario SET ra='$ra',$alteraNome endereco='$endereco',bairro='$bairro',cidade='$cidade',estado='$estado',cep='$cep',nascimento='$nascimento',telefone='$telefone',celular='$celular', email='$email',observacoes='$observacoes',senha=md5('$senha') $adaptado WHERE idUsuario = $id";
                    }
                }
                // echo $sql;
                mysql_query($sql);
                if ($_SESSION["tipo"] == "administrador") {
                    echo "<script>
    alert('Alteração realizada com sucesso!');
    window.location='cadastroUsuario.php';
</script>";
                } else if ($_SESSION["tipo"] == "aluno") {
                    echo "<script>
    alert('Alteração realizada com sucesso!');
    window.location='index.php';
</script>";
                }
            } else if ($operacao == 'excluir') {
                $sql = "DELETE FROM usuario WHERE idusuario = $id";
                //echo $sql;
                mysql_query($sql);
                echo "<script>
    alert('Exclusão realizada com sucesso!');
    window.location='cadastroUsuario.php';
</script>";
            }
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