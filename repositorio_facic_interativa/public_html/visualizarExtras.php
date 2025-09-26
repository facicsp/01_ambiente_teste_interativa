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
            if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
                //conteudo do site
                include "topo.php";
                ?>



                <div class="dados">
                    <?php
                    include './conexao.php';
                    $seguranca = new Seguranca();
                    if(isset($_GET["d"])){
                        $d = $_GET["d"];
                        $sql = "select usuario.nome,disciplina.disciplina,extra.*,tipo.tipo from usuario,disciplina,extra,tipo where disciplina.disciplina = '$d' and extra.idDisciplina = disciplina.idDisciplina and extra.idAluno = usuario.idUsuario and extra.idTipo = tipo.idTipo";
                    
                    }else{
                    $sql = "select usuario.nome,disciplina.disciplina,extra.*,tipo.tipo from usuario,disciplina,extra,tipo where extra.idDisciplina = disciplina.idDisciplina and extra.idAluno = usuario.idUsuario and extra.idTipo = tipo.idTipo";
                        
                    }
                    
                    
                    echo $sql;
                    $result = mysql_query($sql);
                    $linhas = mysql_num_rows($result);
                    if($linhas > 0){
                        echo "<table>"
                        . "<tr>"
                                . "<td>Nome</td>"
                                . "<td>Disciplina</td>"
                                . "<td>Tipo</td>"
                                . "<td>Título</td>"
                                . "<td>Obs</td>"
                                . "<td>Data</td>"
                                . "<td>Arquivo</td>"
                                ."<td>Pontos</td>"
                                . "</tr>";
                                
                        for($i=0;$i<$linhas;$i++){
                        $idExtra = mysql_result($result, $i, "idExtra");
                        $nome = mysql_result($result, $i, "nome");
                        $disciplina = mysql_result($result, $i, "disciplina");
                        $tipo = mysql_result($result, $i, "tipo");
                        $titulo = mysql_result($result, $i, "titulo");
                        $observacao = mysql_result($result, $i, "observacao");
                        $data = mysql_result($result, $i, "data");
                        $arquivo = mysql_result($result, $i, "arquivo");
                        $pontos = mysql_result($result, $i, "pontos");
                        echo "<tr>"
                                . "<td>$nome</td>"
                                . "<td>$disciplina</td>"
                                . "<td>$tipo</td>"
                                . "<td>$titulo</td>"
                                . "<td>$observacao</td>"
                                . "<td>$data</td>"
                                . "<td><a href='$arquivo'>$arquivo</a></td>"
                                . "<td>"
                                . "<form method='post' action='gravarPontoExtra.php'>"
                                . "<input type='hidden' name='idExtra' value='$idExtra'>"
                                . "<input type='text' name='txtPontos' value='$pontos'>"
                                . "<input type='submit' value='Gravar'>"
                                . "</form>"
                                . "</td>"
                                . "</tr>";
                        
                        }
                        echo "</table>";
                    }else{
                        echo "<p>Nenhuma atividade encontrada.</p>";
                    }
                    
                    ?>
            </div>
        </form>
        <hr>


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