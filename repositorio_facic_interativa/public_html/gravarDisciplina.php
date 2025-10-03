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
            $seguranca = new Seguranca();
            $idProfessor = $seguranca->antisql($_POST["txtProfessor"]);
            $idProfessorAux = $seguranca->antisql($_POST["txtProfessorAuxiliar"]);
            $idTurma = $seguranca->antisql($_POST["txtTurma"]);
            $disciplina = $seguranca->antisql($_POST["txtDisciplina"]);
            $cargaHoraria = $seguranca->antisql($_POST["txtCargaHoraria"]);
            $inicio = $seguranca->antisql($_POST["txtInicio"]);
            $termino = $seguranca->antisql($_POST["txtTermino"]);
            $credito = $seguranca->antisql($_POST["txtCredito"]);
            $sql = "INSERT INTO disciplina VALUES(null,'$disciplina','$cargaHoraria', '$credito', '$idProfessor','$idTurma','$inicio','$termino', '" . $_SESSION['semestre'] . "')";
            //echo $sql;
            mysql_query($sql);
            $idDisciplina = mysql_insert_id();  

if ($idProfessorAux > 0) {
    mysql_query("INSERT INTO relacao_professor_auxiliar VALUES (NULL, '$idProfessorAux', '$idDisciplina')");
}

            echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastroDisciplina.php';
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