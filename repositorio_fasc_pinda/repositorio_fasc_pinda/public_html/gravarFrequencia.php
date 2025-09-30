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
            if ($_SESSION["tipo"] == "professor") {
                //$idAluno = $_SESSION["id"];
                include "topo.php";
                include "LoginRestrito/conexao.php";
                $seguranca = new Seguranca();
                $idDisciplina = $seguranca->antisql($_POST["disciplina"]);
                $registros = $seguranca->antisql($_POST["registros"]);
                $data = $seguranca->antisql($_POST["data"]);
                $idAula = $seguranca->antisql($_POST["idAula"]);
                for($i = 0;$i < $registros;$i++){
                    if(isset($_POST["frequencia$i"])){
                    $frequencia[$i] = "F";
                    }else{
                        $frequencia[$i] = "P";
                    }
                    $idAluno[$i] = $seguranca->antisql($_POST["idAluno$i"]);
                    $sql = "INSERT INTO frequencia VALUES(null,'$frequencia[$i]',str_to_date('$data','%d/%m/%Y'),'$idAluno[$i]','$idDisciplina','$idAula')";
                    //echo $sql."<br>";
                    if(mysqli_query($conexao, $sql)){
                        
                    }else{
                        echo mysqli_error($conexao)."<br>";
                    }
                    
                    
                }
                echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastroAula.php';
</script>
";

            }
                
            
                
    } else {
        echo "Acesso negado!;";
        echo "<a href='login.html'>Faça o login!</a>";
    }

?>