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
  if($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor"){
      //conteudo do site
      include "topo.php";
      include "LoginRestrito/conexao.php";
      $seguranca = new Seguranca();
      $idAluno = $seguranca->antisql($_POST["idAluno"]);
      $nome = $seguranca->antisql($_POST["nome"]);
      $email = $seguranca->antisql($_POST["email"]);
?>

        <table width="950px" align="center" id="tabelaprincipal">

            <tr>
                <td>
                    <div id="titulo">
        <h3>Enviar Mensagem - <?php echo $nome;?></h3>
                    </div>
        <form method="post" action="gravarContato.php">
            <div align="center">
            <table id="cadastro">
                <tr>
                    <td>
                        Assunto
                    </td>
                    <td>
                        <input type="text" name="txtAssunto" size="50">

                        

                    </td>
                </tr>
<tr>
                    <td>
                        Mensagem
                    </td>
                    <td>
                        <textarea name="txtMensagem" rows="5" cols="50"></textarea>
                        <?php
                        echo "<input type='hidden' name='idAluno' value='$idAluno'>
                      <input type='hidden' name='nome' value='$nome'>
                      <input type='hidden' name='email' value='$email'>";
                        ?>
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