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
                include "conexao.php";
                $seguranca = new Seguranca();
                if(isset($_GET["id"])){
                $idAula = $seguranca->antisql($_GET["id"]);
                $_SESSION["idAula"] = $idAula;
                }else{
                $idAula = $_SESSION["idAula"];
                }
                $sqlAula = "SELECT descricao FROM aula WHERE idAula = '".$idAula."'";
   //             echo $sqlAula;
                $result = mysql_query($sqlAula);
                $linhas = mysql_num_rows($result);
                $titulo = "";
                if ($linhas > 0) {
                    $titulo = mysql_result($result, 0, "descricao");
                } else {
                    echo "Volte e selecione uma aula!";
                }
                ?>

                <table width="950px" align="center" id="tabelaprincipal">

                    <tr>
                        <td>
                            <div id="titulo">
                                <h3>Cadastro de Conteudos (Arquivos)</h3>
                                <h3>Aula - <?php echo $titulo; ?></h3>
                            </div>
                            <form action="gravarConteudo.php" enctype="multipart/form-data" method="POST"> 
                                <div align="center">
                                    <table id="cadastro">
                                        <tr>
                                            <td>
                                                Título
                                            </td>
                                            <td>
                                                <input type="text" name="txtTitulo">


                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Arquivo
                                            </td>
                                            <td>
                                            <input type="file" name="txtArquivo" >

                                                <input type='submit' value='Gravar'>

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                            <hr>
                            

                            <?php
                            $consulta = "";
                            if (isset($_GET["txtConsulta"])) {
                                $consulta = $_GET["txtConsulta"];
                            }
                            $sql = "SELECT * FROM conteudo WHERE idAula = '$idAula'";
                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);
                            if ($linhas > 0) {
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Título</td>
                <td>Arquivo</td>
                <td colspan='2'>Operações</td>
                
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idConteudo");
                                    $titulo = mysql_result($resultados, $i, "titulo");
                                    $arquivo = mysql_result($resultados, $i, "arquivo");
                                    echo "
                      
                      <tr>
                      <td>$id</td>
                      <td>$titulo</td>
                      <td><a href='downloadFile.php?arquivo=$arquivo&nome=$titulo' target='_blank'><img src='imagens/acessoArquivo.png'>Acesse o arquivo</a></td>
                      
                      <form method='post' action='operacaoConteudo.php'>
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
                            ?>
                        </td>
                    </tr>
                    
                </table>
        <div class="voltar"><a href="cadastroAula.php"><i class="icon small rounded color1 fa-arrow-left"></i> Voltar</a></div>
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