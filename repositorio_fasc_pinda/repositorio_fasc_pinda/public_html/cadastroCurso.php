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
  if($_SESSION["tipo"] == "administrador"){
      //conteudo do site
      include "topo.php";
?>

        <table width="950px" align="center" id="tabelaprincipal">

            <tr>
                <td>
                    <div id="titulo">
        <h3>Cadastro de Cursos</h3>
                    </div>
        <form method="post" action="gravarCurso.php">
            <div align="center">
            <table id="cadastro">
                <tr>
                    <td>
                        Descrição
                    </td>
                    <td>
                        <input type="text" name="txtDescricao">
                        <input type='submit' value='Gravar'>
                        
                    </td>
                </tr>

            </table>
                </div>
        </form>
        <hr>
        <center>
        <form method="get" action="cadastroCurso.php">
            <b>Consultar Cursos</b><input type="text" name="txtConsulta">
            <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
        </form>
        </center>

        <?php
        include 'LoginRestrito/conexao.php';
        $consulta = "";
        if(isset($_GET["txtConsulta"])){
        $consulta = $_GET["txtConsulta"];
        
        $sql = "SELECT * FROM curso WHERE descricao LIKE '%$consulta%'";
        $resultados = mysqli_query($conexao, $sql);
        $linhas = mysqli_num_rows($resultados);
        if ($linhas > 0) {
            echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Descrição</td>
                <td colspan='2'>Operações</td>
                
                </tr>
";

            for($i = 0; $i < $linhas;$i++){
                $id = mysql_result($resultados, $i,"idCurso");
                $descricao = mysql_result($resultados,$i,"descricao");
                
                echo "
                      <form method='post' action='operacaoCurso.php'>
                      <tr>
                      <td>$id</td>
                      <td><input type='text' value='$descricao' name='txtDescricao'></td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoCurso.php'>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='excluir'>
                      <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                      </form>
                      
                      </tr>
";
            }

            echo "</table>";
        } else {
            echo "Nenhuma registro encontrado.";
        }
        }
        ?>
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