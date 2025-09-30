<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

// if (apache_request_headers()["token"] != "w9RWRRIxHnzv8jIU7tO40uGYUTDmGtwW") {
//   echo json_encode(false);
//   exit;
// }

include 'LoginRestrito/conexao.php';

$_POST = json_decode(file_get_contents("php://input"), true);

$semestre = "2025/2"; //"2019/2";
$idCursoPadrao = 8; //53;

$turma = $_POST["turma"]["turma"];
$inicio = $_POST["turma"]["inicio"];
$termino = $_POST["turma"]["termino"];

mysqli_query($conexao, "INSERT INTO turma VALUES (NULL, '$turma', '$idCursoPadrao', 'sim', '$semestre')");
$idTurma = mysqli_insert_id($conexao);

for ($i=0; $i < sizeof($_POST["disciplinas"]); $i++) {
    $nome = $_POST["disciplinas"][$i]["nome"];
    $email = $_POST["disciplinas"][$i]["email"];
    $senha = $_POST["disciplinas"][$i]["senha"];

    $result = mysqli_query($conexao, "SELECT idProfessor FROM professor WHERE email = '$email'");
    if (mysqli_num_rows($result) > 0) {
        $idProfessor = mysql_result($result, 0, "idProfessor");
    } else {
        mysqli_query($conexao, "INSERT INTO professor (nome, email, senha) VALUES ('$nome', '$email', '$senha')");
        $idProfessor = mysqli_insert_id($conexao);
    }

    $disciplina = $_POST["disciplinas"][$i]["disciplina"];
    $adaptados = $_POST["disciplinas"][$i]["adaptados"];
    mysqli_query($conexao, "INSERT INTO disciplina VALUES (NULL, '$disciplina', '50', '0', '$idProfessor', '$idTurma', '$inicio', '$termino', '$semestre')");
    $idDisciplina = mysqli_insert_id($conexao);

    foreach ($adaptados as $aluno) {
        $nome = $aluno["nome"];
        $endereco = $aluno["endereco"];
        $bairro = $aluno["bairro"];
        $cidade = $aluno["cidade"];
        $estado = $aluno["estado"];
        $cep = $aluno["cep"];
        $telefone = $aluno["telefone"];
        $celular = $aluno["celular"];
        $email = $aluno["email"];
        $ra = $aluno["ra"];
      
      
        $senha = md5($ra);

        $result = mysqli_query($conexao, "SELECT idUsuario FROM usuario WHERE ra = '$ra'");
        if (mysqli_num_rows($result) > 0) {
            $idUsuario = mysql_result($result, 0, "idUsuario");
        } else {
            mysqli_query($conexao, "INSERT INTO usuario VALUES (NULL, '$nome', '$endereco', '$bairro', '$cidade', '$estado', '$cep', '2000-01-01', 
                '$telefone', '$celular', '$email', '$senha', 'aluno', '', '$ra', '2000-01-01', '', 0, '')");
            $idUsuario = mysqli_insert_id($conexao);  
        }

        mysqli_query($conexao, "INSERT INTO listadisciplina VALUES (NULL, '$idDisciplina', '$idUsuario', 'sim')");
    }
}

for ($i=0; $i < sizeof($_POST["alunos"]); $i++) {
    $nome = $_POST["alunos"][$i]["nome"];
    $endereco = $_POST["alunos"][$i]["endereco"];
    $bairro = $_POST["alunos"][$i]["bairro"];
    $cidade = $_POST["alunos"][$i]["cidade"];
    $estado = $_POST["alunos"][$i]["estado"];
    $cep = $_POST["alunos"][$i]["cep"];
    $telefone = $_POST["alunos"][$i]["telefone"];
    $celular = $_POST["alunos"][$i]["celular"];
    $email = $_POST["alunos"][$i]["email"];
    $senha = $_POST["alunos"][$i]["senha"];
    $ra = $_POST["alunos"][$i]["ra"];
    $data = $_POST["alunos"][$i]["data"];

    $result = mysqli_query($conexao, "SELECT idUsuario FROM usuario WHERE ra = '$ra'");
    if (mysqli_num_rows($result) > 0) {
        $idUsuario = mysql_result($result, 0, "idUsuario");
    } else {
        mysqli_query($conexao, "INSERT INTO usuario VALUES (NULL, '$nome', '$endereco', '$bairro', '$cidade', '$estado', '$cep', '2000-01-01', 
                '$telefone', '$celular', '$email', '$senha', 'aluno', '', '$ra', '2000-01-01', '', 0, '', '')");
        $idUsuario = mysqli_insert_id($conexao);  
    }
  
    mysqli_query($conexao, "INSERT INTO matricula VALUES (NULL, '$idUsuario', '$idTurma', NOW())");
}

echo "ok";