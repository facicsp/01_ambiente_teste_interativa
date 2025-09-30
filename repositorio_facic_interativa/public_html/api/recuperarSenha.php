<?php
    
include 'hasAccess.php';

$seguranca = new Seguranca();
$email = $seguranca->antisql($_POST["email"]);

$result = mysqli_query($conexao, "SELECT idUsuario FROM usuario WHERE email='$email' AND tipo='aluno'");

if(mysqli_num_rows($result) > 0) {
    $senha = substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )), 1, 10);

    mysqli_query($conexao, "UPDATE usuario SET recuperar_senha='$senha' WHERE email='$email'");
    // ENVIAR EMAIL COM A NOVA SENHA -- ESSE ARQUIVO PRECISA ESTAR HOSPEDADO PARA FAZER ISSO --
    echo json_encode(true);
} else {
    echo json_encode(false);
}

?>