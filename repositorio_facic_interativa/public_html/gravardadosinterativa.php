<?php
header("Access-Control-Allow-Origin: http://ava24horas.com");
$code = $_REQUEST["code"];
if($code == "dhdhd7dyuy78iuh3uy78yueh28"){
    include './conexao.php';
    $curso = $_REQUEST["curso"];
    $turma = $_REQUEST["turma"];
    $professor = $_REQUEST["professor"];
    $disciplina = $_REQUEST["disciplina"];
    $gravarcurso = $_REQUEST["gravarcurso"];
    $gravarturma = $_REQUEST["gravarturma"];
    $gravarprofessor = $_REQUEST["gravarprofessor"];
    $email = $_REQUEST["email"];
    $senha = $_REQUEST["senha"];
    $datainicial = $_REQUEST["datainicial"];
    $datafinal = $_REQUEST["datafinal"];
    
    if($gravarcurso == "sim"){
        $sql = "INSERT INTO curso VALUES(null,'$curso')";
        mysql_query($sql);
        $curso = mysql_insert_id();
    }
    
    if($gravarturma == "sim"){
        $sql = "INSERT INTO turma VALUES(null,'$turma','$curso','sim')";
        mysql_query($sql);
        $turma = mysql_insert_id();
    }
    
    if($gravarprofessor == "sim"){
        $sql = "INSERT INTO professor VALUES(null,'$professor','$email','$senha')";
        mysql_query($sql);
        $professor = mysql_insert_id();
    }
    
    $sql = "INSERT INTO disciplina VALUES(null,'$disciplina','0','$professor','$turma','$datainicial','$datafinal')";
    mysql_query($sql);
    
    $retorno = "<p>Dados gravados!</p>";
    $retorno .= "<input type='hidden' id='turmagravada' value='$turma'>";
    echo $retorno;
    
}else{
    echo "Acesso limitado. Entre em contato com o desenvolvedor.";
}


?>
