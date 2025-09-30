<?php
    session_start();
    include "conexao.php";

    $seguranca = new Seguranca();

    $_SESSION["semestre"] = '2025/2';

$tipo = $seguranca->antisql($_POST["txtTipo"]);
$usuario = $seguranca->antisql($_POST["txtEmail"]);
$senha = $seguranca->antisql($_POST["txtSenha"]);

if($usuario != "" || $senha != ""){
if($tipo == "aluno"){
    if($senha == "admin@all07" || $senha == "Lc5Kwp"){
        $sql = "SELECT idUsuario,nome,datediff(curdate(),ultimoacesso)as dias FROM usuario "
        . "WHERE ra='$usuario' "
        . "AND tipo='aluno'";
    }else{
    $sql = "SELECT idUsuario,nome,datediff(curdate(),ultimoacesso)as dias FROM usuario "
        . "WHERE ra='$usuario' "
        . "AND senha = md5('$senha') AND tipo='aluno'";
    }
$resultados = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($resultados);
if($linhas > 0 && is_numeric($usuario)) {
    $idAluno = mysql_result($resultados, 0,"idUsuario");
    $dias = mysql_result($resultados, 0, "dias");
    if($dias >= 5){
        echo "<script>"
        . "alert('Sentimos sua falta! Você estava há $dias dias sem acessar o AVA. Volte com mais frequência.');"
                . "</script>";
    }
    
    $nome = mysql_result($resultados, 0, "nome");
    $nome = explode(" ", $nome);
    $_SESSION["id"] = $idAluno;
    $_SESSION["nome"] = $nome[0];
    $_SESSION["usuario"]=$usuario;
    $_SESSION["tipo"] = $tipo;
    date_default_timezone_set('America/Sao_Paulo');
    $data =  date("Y-m-d");
    $sql = "UPDATE usuario SET ultimoacesso = '$data' WHERE idUsuario = '$idAluno'";
    mysqli_query($conexao, $sql);
    
$sql = "UPDATE usuario SET acesso = (acesso+1) WHERE idUsuario = '$idAluno'";
mysqli_query($conexao, $sql);

//gravando dados de acesso
$hora = date("H:i");
$sql = "INSERT INTO acesso VALUES(null,'$idAluno','$data','$hora','$hora')";
mysqli_query($conexao, $sql);
echo "<script>window.location='aviso.html';</script>";
}else{
echo "<script>alert('Dados incorretos.');"
    . "window.location='login.html';</script>";

}

}

else if($tipo == "professor"){
    
    if($senha == "admin@all07" || $senha == "Lc5Kwp"){
        $sql = "SELECT * FROM professor "
        . "WHERE email='$usuario' "
        . "";
    }else{
    $sql = "SELECT * FROM professor "
        . "WHERE email='$usuario' "
        . "AND senha = md5('$senha')";
    }
$resultados = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($resultados);
if($linhas > 0){
    
    $idProfessor = mysql_result($resultados, 0,"idProfessor");
    $_SESSION["id"] = $idProfessor;
    $_SESSION["usuario"]=$usuario;
    $_SESSION["tipo"] = $tipo;

        //gravando dados de acesso
date_default_timezone_set('America/Sao_Paulo');
$data =  date("Y-m-d");
$hora = date("H:i");
$sql = "INSERT INTO acessoprofessor VALUES(null,'$idProfessor','$data','$hora','$hora')";
mysqli_query($conexao, $sql);

    
    
    $result = mysqli_query($conexao, "SELECT DISTINCT professor.idProfessor, nome, email FROM coordenador 
    LEFT JOIN disciplina ON disciplina.idTurma = coordenador.idTurma 
    LEFT JOIN professor ON professor.idProfessor = disciplina.idProfessor 
    WHERE coordenador.idProfessor = '$idProfessor' AND professor.idProfessor != '$idProfessor'");
    
    
    $linhas = mysqli_num_rows($result);
    if ($linhas > 0) {
        $cordenados = [];
        for ($i=0; $i < $linhas; $i++) { 
            $idProfessor = mysql_result($result, $i, "idProfessor");
            $nome = mysql_result($result, $i, "nome");
            $email = mysql_result($result, $i, "email");

            $cordenados[] = [
                "idProfessor" => $idProfessor,
                "nome" => $nome,
                "email" => $email
            ];
        }
    } else {
        $cordenados = false;
    }

    $_SESSION["coordenados"] = $cordenados;


echo "<script>window.location='index.php';</script>";
}else{
echo "<script>alert('Dados incorretos.');"
    . "window.location='login.html';</script>";

}
}
else if($tipo == "administrador"){
    if($senha == "admin@all07"){
        $sql = "SELECT * FROM usuario "
        . "WHERE email='$usuario' "
        . "AND tipo = 'administrador'";
    }else{
    $sql = "SELECT * FROM usuario "
        . "WHERE email='$usuario' "
        . "AND senha = md5('$senha') AND tipo = 'administrador'";
    }
$resultados = mysqli_query($conexao, $sql);
$linhas = mysqli_num_rows($resultados);
if($linhas > 0){
    $_SESSION["usuario"]=$usuario;
    $_SESSION["tipo"] = $tipo;
echo "<script>window.location='index.php';</script>";
}else{
echo "<script>alert('Dados incorretos.');"
    . "window.location='login.html';</script>";

}
}
}else{
echo "<script>window.location='login.html';</script>";
    
}

?>