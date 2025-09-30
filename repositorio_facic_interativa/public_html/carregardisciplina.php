<?php
session_start();
if (isset($_SESSION["usuario"])) {
    if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "administrador") {

        include 'LoginRestrito/conexao.php';
        $seguranca = new Seguranca();
        $idTurma = $seguranca->antisql($_POST["idTurma"]);
        $idProfessor = $seguranca->antisql($_POST["idProfessor"]);

        if ($idProfessor == 0) {
            $sql = "SELECT d.*,t.turma FROM disciplina d,turma t where d.idTurma = '$idTurma' and d.idTurma = t.idTurma ORDER BY d.disciplina";
        } else {
            $sql = "SELECT d.*,t.turma FROM disciplina d,turma t where d.idTurma = t.idTurma and d.idProfessor = '$idProfessor' and d.idTurma = '$idTurma' ORDER BY d.disciplina";
        }
        $resultados = mysqli_query($conexao, $sql);
        $linhas = mysqli_num_rows($resultados);
        if ($linhas > 0) {
            $retorno = "<option>Escolha a disciplina</option>";
            for ($i = 0; $i < $linhas; $i++) {

                $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                $disciplina = mysql_result($resultados, $i, "disciplina");
                $turma = mysql_result($resultados, $i, "turma");
                /*if ($_SESSION["tipo"] == "professor") {

                    if ($i == 0) {
                        $listaDisciplina.= $idDisciplina;
                    } else {
                        $listaDisciplina.= "," . $idDisciplina;
                    }
                }*/
                $retorno .= "<option value='$idDisciplina'>$disciplina - $turma</option>";
            }
            echo $retorno;
        }
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