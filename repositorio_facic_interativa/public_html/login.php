<?php 
  session_start(); 
  
  $_SESSION["in_app"] = true; 
  
  $tipo = isset($_GET["aluno"]) ? "aluno" : "professor";
  
  $_SESSION["tipo_login_app"] = $tipo;
  
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/cadastro.css" type="text/css" media="screen">
    <title>INTERATIVA</title>
    <style>
      label{
        color:#069;
      }
      .hide {
        display: none;
        
      }
      html, body {
        padding: 0 !important;
      }
      input {
        padding: 36px;
      }
      
      * {
        font-size: 106%;
      }
    </style>
  </head>
  <body>
    <div class="show-in-app">
      <div id="titulo" style="width: 100%">
                     <center><img style="width: 100%;" src="imagens/logo.jpg"></center>
        <h3>Login</h3>
      </div>
      <form method="post" action="validar.php">
        <input type="hidden" name="txtTipo" value="<?= $tipo ?>">
        
        <span id="user" style="color: #3186A7; padding: 0 !important;">Usuário</span><br>
        <input style='width: 100%; padding: 20px' type="text" name="txtEmail" required><br><br>
        
        <span style="color: #3186A7; padding: 0 !important;">Senha</span><br>
        <input style='width: 100%; padding: 20px' type="password" name="txtSenha" required><br>
        
        <input type="image" src="imagens/login.png">
      </form>
      
      <a href="redefinir.php">Esqueci a senha</a>
    </div>
    
  </body>
  <script src="js/jquery.min.js"></script>
</html>