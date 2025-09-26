<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
        

        <link rel="stylesheet" href="css/cadastro.css">

    </head>
    <body>
        <?php
        if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "aluno") {
        $idProfessor = $_SESSION["id"];
        include "topo.php";
        if($_SESSION["tipo"] == "aluno"){
        ?>
        <div  class="botoes">
        <a href="cadastroDuvida.php"><div>Nova Dúvida</div></a>
        </div>
        <?php
        }
        ?>
        
        <div class="dados">
            <div class="barratitulo"><h1>Minhas Mensagens</h1></div>
            <?php
            include "conexao.php";
            $seguranca = new Seguranca();
            $id = $_SESSION["id"];
            if($_SESSION["tipo"] == "professor"){
            $sql = "select contato.*,usuario.nome,professor.nome as professor from contato,usuario,professor where contato.idProfessor = '$id' and contato.idAluno = usuario.idUsuario and contato.idProfessor = professor.idprofessor order by idcontato desc";
            }
            else if($_SESSION["tipo"] == "aluno"){
            $sql = "select contato.*,usuario.nome,professor.nome as professor from contato,usuario,professor where idAluno = '$id' and contato.idAluno = usuario.idUsuario and contato.idProfessor = professor.idprofessor order by idcontato desc";
            }

            $result = mysql_query($sql);
            $linhas = mysql_num_rows($result);
            if ($linhas > 0) {

            for ($i = 0;$i < $linhas;$i++) {
            $idContato = mysql_result($result, $i, "idContato");
            $assunto = mysql_result($result, $i, "assunto");
            $nome = mysql_result($result, $i, "nome");
            $professor = mysql_result($result, $i, "professor");
            $tipo = $_SESSION["tipo"];
            $_SESSION["idContato"][$i] = $idContato;
            
            $consultaStatus = "select idMensagem from mensagem where idContato = '$idContato' and tipo <> '$tipo' and status = 'nao'";
            $resultadoStatus = mysql_query($consultaStatus);
            $linhasStatus = mysql_num_rows($resultadoStatus);
            
            if ($assunto == "") $assunto = "SEM ASSUNTO";
            
            echo "<div class='lista' style='margin-top:5px;'>::<a href='visualizarMensagem.php?id=$i'>$assunto</a>";
                    
                    if ($_SESSION["tipo"] == "aluno") {
                        echo ":: - Professor: $professor";
                    } else if ($_SESSION["tipo"] == "professor") {
                        echo ":: - Aluno: $nome";
                        $resultNome = mysql_query("SELECT turma FROM contato 
                                                    LEFT JOIN matricula ON matricula.idAluno = contato.idAluno 
                                                    LEFT JOIN turma ON turma.idTurma = matricula.idTurma 
                                                    WHERE idContato = '$idContato' ORDER BY idMatricula DESC");

                        if (mysql_num_rows($resultNome) > 0) {
                            $turma = mysql_result($resultNome, 0, "turma");
                            echo " - $turma";
                        }

                    }
                    
                    if($linhasStatus > 0) {
                        echo " - <span>($linhasStatus não lida(s))</span>";
                    }
                    
                    echo "</div>";
            
                      
            }
            }else {
            echo "Nenhuma registro encontrado.";
            }
            ?>

        </table>
        <hr>
        <div class="voltar"><a href="index.php"><img src="imagens/voltar.png">Voltar</a></div>

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
}else {
echo "<script>"
. "alert('É necessário fazer o login!');"
. "window.location='login.html';"
. "</script>";
}
?>