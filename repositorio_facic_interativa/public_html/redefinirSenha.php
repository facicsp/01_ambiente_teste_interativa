<?php
session_start();
include "conexao.php";
$seguranca = new Seguranca();
if(isset($_SESSION["senha2"])){
    $senha = $seguranca->antisql($_POST["txtSenha"]);
    $idUsuario = $_SESSION["idUsuario"];
    $sql = "UPDATE usuario SET senha = md5('$senha') WHERE idUsuario='$idUsuario'";
    mysql_query($sql);
    unset($_SESSION["senha2"]);
    unset($_SESSION["email"]);
    unset($_SESSION["idUsuario"]);
    echo "<script>alert('Senha atualizada!');"
    . "window.location='login.html';</script>";


}else{
$idUsuario = $seguranca->antisql($_GET["email"]);
$codigo = $seguranca->antisql($_GET["codigo"]);

    $sql = "SELECT * FROM usuario "
        . "WHERE idUsuario='$idUsuario' "
        . "AND senha2='$codigo'";
$resultados = mysql_query($sql);
$linhas = mysql_num_rows($resultados);
if($linhas > 0){
    
    $_SESSION["senha2"]="ok";
    $_SESSION["idUsuario"] = $idUsuario;
    ?>
<form method="post" action="redefinirSenha.php">
    <label>Digite a nova senha:</label>
    <input type="password" name="txtSenha">
    <input type="submit" value="Redefinir Senha">
       
</form>
<?php
}else{
echo "<script>alert('Dados incorretos.');"
    . "window.location='login.html';</script>";

}
}


?>