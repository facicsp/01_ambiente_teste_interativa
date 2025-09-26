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
        if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "aluno") {
        $idProfessor = $_SESSION["id"];
        include "topo.php";
        ?>


        <div class="dados">
            <div class="barratitulo"><h1>Visualizar Mensagens</h1></div>
            <?php
            include "conexao.php";
            $seguranca = new Seguranca();
            $idContato = $seguranca->antisql($_GET["id"]);
            $idContato = $_SESSION["idContato"][$idContato];
            echo "<form method='post' action='gravarMensagem.php'>"
            . "<input type='hidden' name='idContato' value='$idContato'>";
            echo "Responder:<textarea rows='4' cols='50' name='txtMensagem' style='color:#000;'></textarea>"
            . "<input type='submit' value='Responder'>";
            $sql = "select *,date_format(data,'%d/%m/%Y')as dataMensagem from mensagem where idcontato = '$idContato' order by idMensagem desc";
            //echo $sql;
            $result = mysql_query($sql);
            $linhas = mysql_num_rows($result);
            if ($linhas > 0) {

            for ($i = 0;$i < $linhas;$i++) {
            $idMensagem = mysql_result($result, $i, "idMensagem");
            $mensagem = mysql_result($result, $i, "mensagem");
            $data = mysql_result($result, $i, "dataMensagem");
            $tipo = ucwords(mysql_result($result, $i, "tipo"));
            $status = mysql_result($result, $i, "status");
            
            
            if($tipo != ucwords($_SESSION["tipo"]) && $status == "nao"){
                $sqlMensagem = "UPDATE mensagem SET status = 'sim' WHERE idMensagem = '$idMensagem'";
                mysql_query($sqlMensagem);
            }
            
            if($status == "sim"){
            echo "<div class='lista' style='margin-top:5px;height:min-height:50px;'>";
            }else{
            echo "<div class='lista' style='margin-top:5px;height:min-height:50px;background-color:#cc0033;'>";
            }
            echo  "<p>Enviado por: $tipo em $data</p><hr>"
                    . "<p style='text-align:justify;'>$mensagem</p>"
                    
                    . "</div>";
            
                      
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