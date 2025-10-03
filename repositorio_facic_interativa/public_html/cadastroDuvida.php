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
  if($_SESSION["tipo"] == "aluno"){
      //conteudo do site
      include "topo.php";
      include "./conexao.php";
      $seguranca = new Seguranca();
      $idAluno = $_SESSION["id"];
?>

        <table width="950px" align="center" id="tabelaprincipal">

            <tr>
                <td>
                    <div id="titulo">
        <h3>Enviar Dúvida</h3>
                    </div>
        <form method="post" action="gravarDuvida.php">
            <div align="center">
            <table id="cadastro">
                <tr>
                    <td>
                        Assunto
                    </td>
                    <td>
                        <input type="text" name="txtAssunto" size="50" required>

                        

                    </td>
                </tr>
<tr>
                    <td>
                        Mensagem
                    </td>
                    <td>
                        <textarea name="txtMensagem" rows="5" cols="50" required></textarea>
                        <?php echo "<input type='hidden' name='idAluno' value='$idAluno'>"; ?>
                    </td></tr>
<tr>
                    <td>Escolha o Professor</td>
                    <td>
                        <select name="txtProfessor">
                            <?php
        $semestre = $_SESSION["semestre"];
                            // $sql = "select p.idProfessor,p.nome from disciplina,professor p where idTurma in(select idTurma from matricula where idAluno = '$idAluno') 
                            // and disciplina.idProfessor = p.idProfessor group by nome";
                            
                            $sql = "select disciplina.*,professor.nome, professor.idProfessor from disciplina,professor 
where idturma in(select idturma from matricula where idaluno = '$idAluno') 
AND disciplina.idProfessor = professor.idProfessor AND semestre = '$semestre'";
                            
                            
                            $result = mysql_query($sql);
                            $linhas = mysql_num_rows($result);
                            if($linhas > 0){
                                for($i=0;$i<$linhas;$i++){
                                    $idProfessor = mysql_result($result, $i, "idProfessor");
                                    $nome = mysql_result($result, $i, "nome");
                                    echo "<option value='$idProfessor'>$nome</option>";
                                }
                            }
                            
                            $sql = "select disciplina.disciplina,usuario.nome,listadisciplina.idListaDisciplina,listadisciplina.ativo,professor.nome as professor,professor.idProfessor, listadisciplina.idDisciplina
from listadisciplina,usuario,disciplina,professor
WHERE listadisciplina.idAluno = '$idAluno'
AND listadisciplina.idDisciplina = disciplina.idDisciplina
AND listadisciplina.idAluno = usuario.idUsuario
AND disciplina.idProfessor = professor.idProfessor AND semestre = '$semestre'";
                            
                             $result = mysql_query($sql);
                            $linhas = mysql_num_rows($result);
                            if($linhas > 0){
                                for($i=0;$i<$linhas;$i++){
                                    $idProfessor = mysql_result($result, $i, "idProfessor");
                                    $nome = mysql_result($result, $i, "professor");
                                    echo "<option value='$idProfessor'>$nome</option>";
                                }
                            }
                                    
                            ?>
                        </select>
                    </td>
                        <input type='submit' value="Enviar Mensagem" title='Gravar'>

                    </td>
                </tr>

            </table>
                </div>
        </form>
        <hr>
        
        </td>
        </tr>

        </table>

    </body>
</html>
<?php
  }else{
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