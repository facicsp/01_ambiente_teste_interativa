<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
    </head>
    <body>
        <?php
        session_start();
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "professor") {
                //conteudo do site


                include 'LoginRestrito/conexao.php';

                $seguranca = new Seguranca();
                $idUsuario = $_SESSION["id"];
                $tipo = $_SESSION["tipo"];
                $Titulo = $seguranca->antisql($_POST["txtTitulo"]);
                $Conteudo = $seguranca->antisql($_POST["txtConteudo"]);
                $Status = $seguranca->antisql($_POST["txtStatus"]);
                $avaliativo = $seguranca->antisql($_POST['txtAvaliativo']);
                $IdDirecionado = 0;
                $IdTurma = 0;
                $IdDisciplina = 0;
                $IdTurma = 0;

                if(isset($_POST["txtDisciplina"])){
                    $IdDisciplina = $seguranca->antisql($_POST["txtDisciplina"]);

                    if($IdDisciplina != 0){
                        $sqlProfessor = "SELECT idProfessor,idTurma FROM disciplina WHERE idDisciplina = '$IdDisciplina'";
                        $result = mysqli_query($conexao, $sqlProfessor);
                        $IdDirecionado = mysql_result($result, 0, "idProfessor");
                        $IdTurma = mysql_result($result, 0, "idTurma");
                    }
                }
                
                $sql = "INSERT INTO topico VALUES(null,'$idUsuario','$tipo','$Titulo','$Conteudo','$Status','$IdDirecionado','$IdTurma','$IdDisciplina')";
                // echo $sql;
                mysqli_query($conexao, $sql);

                $idTopico = mysql_insert_id($conexao);

                if ($avaliativo) {
                    $bimestre = $seguranca->antisql($_POST['txtBimestre']);

                    mysqli_query($conexao, "INSERT INTO forumavaliacao VALUES (NULL, $IdDisciplina, $idTopico, $bimestre)");
                }
                
                echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'cadastrotopico.php';
</script>";
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