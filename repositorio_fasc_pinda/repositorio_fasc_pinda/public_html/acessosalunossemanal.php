<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
        <style>
            h3{
                color:#000;
            }
            p{
                text-align: right;
                color:#000;
            }
        </style>
    </head>
    <body>
        <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador") {

                //atividade do site
                include "conexao2.php";
                include "header.php";
                $seguranca = new Seguranca();
                $databusca = $seguranca->antisql($_POST["txtSemana"]);
                $datafinal = date('Y-m-d', strtotime("+4 days", strtotime($databusca)));
                $idTurma = $seguranca->antisql($_POST["idTurma"]);
                $turma = $seguranca->antisql($_POST["turma"]);
                $dataformatada = date('d/m/Y', strtotime("+0 days", strtotime($databusca)));
                $dataformatada2 = date('d/m/Y', strtotime("+0 days", strtotime($datafinal)));
                ?>

                <div>
                    <?php
                    echo "<h3>Relatório de Frequência por Semana</h3>";
                    echo "<p>Semana de $dataformatada a $dataformatada2</p>";
                    echo "<p>Turma: $turma</p>";
                    ?>
                </div>

                <hr>

                <?php
                $sql = "select u.idUsuario,u.ra,u.nome,t.turma 
                        from usuario u,matricula m,turma t
                        where m.idTurma = '$idTurma'
                        and m.idAluno = u.idUsuario
                        and m.idTurma = t.idTurma
                        ORDER BY u.nome";
                //echo $sql;
                $resultados = mysqli_query($conexao, $sql);
                $linhas = mysqli_num_rows($resultados);
                if ($linhas > 0) {
                    echo "<table border='0' align='center' class='relatorio' cellpadding='5' cellspacing='0'>
                <tr>
                <td>RA</td>
                <td>Nome</td>
                <td>Seg</td>
                <td>Ter</td>
                <td>Qua</td>
                <td>Qui</td>
                <td>Sex</td>
                <td>Total de Presenças</td>
                <td>Total de Faltas</td>
                </tr>
";

                    while ($dados = mysqli_fetch_array($resultados)) {
                        $id = $dados["idUsuario"];
                        $ra = $dados["ra"];
                        $nome = $dados["nome"];
                        echo "<tr>"
                        . "<td>$ra</td>"
                        . "<td>$nome</td>";
                        //$turma = $dados["turma"];
                        $datasemana = $databusca;
                        $presenca = 0;
                        $falta = 0;
                        for ($i = 0; $i < 5; $i++) {

                            $sql2 = "SELECT horaentrada FROM acesso WHERE idusuario = '$id' AND data='$datasemana' LIMIT 1";
                            $result2 = mysqli_query($conexao, $sql2);
                            if (mysqli_num_rows($result2) > 0) {
                                echo "<td>P</td>";
                                $presenca++;
                            } else {
                                echo "<td>F</td>";
                                $falta++;
                            }
                            $datasemana = date('Y-m-d', strtotime("+1 days", strtotime($datasemana)));
                        }
                        echo "<td>$presenca</td>"
                        . "<td>$falta</td></tr>";
                    }

                    echo "</table>";
                } else {
                    echo "Nenhuma registro encontrado.";
                }
                ?>

                <script>
                    print();
                </script>
            </body>
        </html>
        <?php
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