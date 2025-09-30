<?php
header("Access-Control-Allow-Origin: http://ava24horas.com");
$code = $_REQUEST["code"];
if($code == "dhdhd7dyuy78iuh3uy78yueh28"){
    $retorno = "";
    $ra = $_REQUEST["ra"];
    $idTurma = $_REQUEST["idturma"];
        
    include 'LoginRestrito/conexao.php';
    $sql = "SELECT idUsuario,ra FROM usuario WHERE ra = '$ra'";
    $retorno .= $sql+"<br>";
    $result = mysqli_query($conexao, $sql);
    if($ra > 0){
    if(mysqli_num_rows($result)==0){
     
        $nome = $_REQUEST["nome"];
        $endereco = $_REQUEST["endereco"];
        $bairro = $_REQUEST["bairro"];
        $cidade = $_REQUEST["cidade"];
        $estado = $_REQUEST["estado"];
        $cep = $_REQUEST["cep"];
        $nascimento = $_REQUEST["nascimento"];
        $telefone = $_REQUEST["telefone"];
        $celular = $_REQUEST["celular"];
        $email = $_REQUEST["email"];
        $senha = $_REQUEST["senha"];
        
        $sql2 = "INSERT INTO usuario VALUES(null,'$nome','$endereco','$bairro','$cidade','$estado','$cep','$nascimento','$telefone','$celular','$email','$senha','aluno','','$ra','0000-00-00','','0')";
        $retorno .= $sql2+"<br>";
        mysqli_query($conexao, $sql2);
        $idAluno = mysqli_insert_id($conexao);
        
    }else{
        $idAluno = mysql_result($result, 0, "idUsuario");
    }
    $diaHoje = date('Y-m-d');

    $sqlMatricula = "SELECT * from matricula WHERE idAluno = '$idAluno' AND idTurma = '$idTurma'";
    $resultMatricula = mysqli_query($conexao, $sqlMatricula);
    if(mysqli_num_rows($resultMatricula) == 0){
    
    $sql3 = "INSERT INTO matricula VALUES(null,'$idAluno','$idTurma','$diaHoje')";
    mysqli_query($conexao, $sql3);
    $retorno = $sql3+"<br>";
    }
    
    //echo $sql3;
    }
}else{
    echo "Acesso limitado. Entre em contato com o desenvolvedor.";
}




?>