<?php
session_start();
if (isset($_SESSION["usuario"])) {
    if ($_SESSION["tipo"] == "professor") {
        
        $idAluno = $_REQUEST["idAluno"];
        $idDisciplina = $_REQUEST["idDisciplina"];
        $idTopico = $_REQUEST["idTopico"];
        $nota = $_REQUEST["nota"];

        include 'conexao.php';

        $sql = "select * from notaforum where idAluno = '$idAluno' AND idDisciplina = '$idDisciplina' AND idTopico = '$idTopico'";
        $result = mysql_query($sql);
        $linhas = mysql_num_rows($result);
        $retorno = "";
        
        if ($linhas > 0) {
            $idNotaForum = mysql_result($result, 0, "idNotaForum");
            $sql = "UPDATE notaforum SET nota = '$nota' WHERE  idNotaForum = '$idNotaForum' AND idAluno = '$idAluno' AND idDisciplina = '$idDisciplina' AND idTopico = '$idTopico'";
            mysql_query($sql);
            $retorno = "Nota atualizada com sucesso.";
            // $retorno = "$sql";
        } else {
            $sql = "INSERT INTO notaforum VALUES(null,'$idAluno','$idDisciplina','$idTopico','$nota')";
            mysql_query($sql);
            $retorno = "Nota gravada com sucesso.";
        }
        
        echo $retorno;
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