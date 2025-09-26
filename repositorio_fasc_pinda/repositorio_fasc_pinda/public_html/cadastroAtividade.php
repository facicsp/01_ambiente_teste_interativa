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
            if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "aluno") {
                //atividade do site
                include "topo.php";
                include "conexao.php";
                $seguranca = new Seguranca();
                /*
                if(isset($_GET["id"])){
                $idAula = $seguranca->antisql($_GET["id"]);
                $_SESSION["idAula"] = $idAula;
                }else{
                $idAula = $_SESSION["idAula"];
                }
                 * 
                 */
                $idAula = $_SESSION["idAulaGeral"];
                $sqlAula = "SELECT descricao FROM aula WHERE idAula = '".$idAula."'";
                //echo $sqlAula;
                $result = mysql_query($sqlAula);
                $linhas = mysql_num_rows($result);
                $titulo = "";
                if ($linhas > 0) {
                    $titulo = mysql_result($result, 0, "descricao");
                } else {
                    echo "Volte e selecione uma aula!";
                }
                date_default_timezone_set('America/Sao_Paulo');
                $dataExibicao = Date("d/m/Y");
                
                $data = Date("Y-m-d");
                $hora = Date("H:i");
                ?>
                <div class="grid-80 prefix-10 suffix-10 principal">
                <table width="950px" align="center" id="tabelaprincipal">

                    <tr>
                        <td>
                            <div class="dados">
                            <div id="titulo">
                                <h3>Cadastro de Atividade</h3>
                                <h3>Aula - <?php echo $titulo; ?></h3>
                            </div>
                            <div id="formulario">
                            <form action="gravarAtividade.php" enctype="multipart/form-data" method="POST"> 
                                <div align="center">
                                    <table id="cadastro">
                                        <?php
                                        echo "<input type='hidden' name='data' value='$data'>";
                                        echo "<input type='hidden' name='hora' value='$hora'>";
                                        echo "<tr><td>Data e Hora</td>"
                                        . "<td>$dataExibicao - $hora</td></tr>";
                                        ?>
                                        <tr>
                                            <td>
                                                Título
                                            </td>
                                            <td>
                                                <input type="text" name="txtTitulo" value="<?php echo "Atividade $titulo";?>">


                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Arquivo
                                            </td>
                                            <td>
                                            <input type="file" name="txtArquivo" >

                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Observações
                                            </td>
                                            <td>
                                                <textarea name="txtObservacao" rows="5" cols="50"></textarea>

                                                
                                                <input type="submit" value="Enviar Atividade">

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form></div>
                            <hr>
                            

                            <?php
                            $consulta = "";
                            if (isset($_GET["txtConsulta"])) {
                                $consulta = $_GET["txtConsulta"];
                            }
                            $sql = "SELECT * FROM atividade WHERE idAula = '$idAula' AND idAluno = '".$_SESSION["id"]."'";
                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);
                            if ($linhas > 0) {
                            echo "<script>"
                                . "document.getElementById('formulario').style.display='none';"
                                    . "</script>";
                            echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Título</td>
                <td>Arquivo</td>
                <td colspan='2'>Operações</td>
                
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idAtividade");
                                    $titulo = mysql_result($resultados, $i, "titulo");
                                    $arquivo = mysql_result($resultados, $i, "arquivo");
                                    if($arquivo == ""){
                                        $arquivo = "Não enviado! Clique no X ao lado para poder enviar o arquivo!";
                                    }else{
                                        $arquivo = "<a href='$arquivo' target='_blank'>Acesse o arquivo</a>";
                                    }
                                    echo "
                      
                      <tr>
                      <td>$id</td>
                      <td>$titulo</td>
                      <td>$arquivo</td>
                      
                      <form method='post' action='operacaoAtividade.php'>
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
</div>                
</div>

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