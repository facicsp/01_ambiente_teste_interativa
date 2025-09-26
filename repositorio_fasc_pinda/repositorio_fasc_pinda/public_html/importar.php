<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

// if (apache_request_headers()["token"] != "w9RWRRIxHnzv8jIU7tO40uGYUTDmGtwW") {
//   echo json_encode(false);
//   exit;
// }

include './conexao.php';

$_POST = json_decode(file_get_contents("php://input"), true);

$semestre = "2025/2"; //"2019/2";
$idCursoPadrao = 8; //53;

$turma = $_POST["turma"]["turma"];
$inicio = $_POST["turma"]["inicio"];
$termino = $_POST["turma"]["termino"];

mysql_query("INSERT INTO turma VALUES (NULL, '$turma', '$idCursoPadrao', 'sim', '$semestre')");
$idTurma = mysql_insert_id();

for ($i=0; $i < sizeof($_POST["disciplinas"]); $i++) {
    $nome = $_POST["disciplinas"][$i]["nome"];
    $email = $_POST["disciplinas"][$i]["email"];
    $senha = $_POST["disciplinas"][$i]["senha"];

    $result = mysql_query("SELECT idProfessor FROM professor WHERE email = '$email'");
    if (mysql_num_rows($result) > 0) {
        $idProfessor = mysql_result($result, 0, "idProfessor");
    } else {
        mysql_query("INSERT INTO professor (nome, email, senha) VALUES ('$nome', '$email', '$senha')");
        $idProfessor = mysql_insert_id();
    }

    $disciplina = $_POST["disciplinas"][$i]["disciplina"];
    $adaptados = $_POST["disciplinas"][$i]["adaptados"];
    mysql_query("INSERT INTO disciplina VALUES (NULL, '$disciplina', '50', '0', '$idProfessor', '$idTurma', '$inicio', '$termino', '$semestre')");
    $idDisciplina = mysql_insert_id();

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

        $result = mysql_query("SELECT idUsuario FROM usuario WHERE ra = '$ra'");
        if (mysql_num_rows($result) > 0) {
            $idUsuario = mysql_result($result, 0, "idUsuario");
        } else {
            mysql_query("INSERT INTO usuario VALUES (NULL, '$nome', '$endereco', '$bairro', '$cidade', '$estado', '$cep', '2000-01-01', 
                '$telefone', '$celular', '$email', '$senha', 'aluno', '', '$ra', '2000-01-01', '', 0, '')");
            $idUsuario = mysql_insert_id();  
        }

        mysql_query("INSERT INTO listadisciplina VALUES (NULL, '$idDisciplina', '$idUsuario', 'sim')");
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

    $result = mysql_query("SELECT idUsuario FROM usuario WHERE ra = '$ra'");
    if (mysql_num_rows($result) > 0) {
        $idUsuario = mysql_result($result, 0, "idUsuario");
    } else {
        mysql_query("INSERT INTO usuario VALUES (NULL, '$nome', '$endereco', '$bairro', '$cidade', '$estado', '$cep', '2000-01-01', 
                '$telefone', '$celular', '$email', '$senha', 'aluno', '', '$ra', '2000-01-01', '', 0, '', '')");
        $idUsuario = mysql_insert_id();  
    }
  
    mysql_query("INSERT INTO matricula VALUES (NULL, '$idUsuario', '$idTurma', NOW())");
}

echo "ok";