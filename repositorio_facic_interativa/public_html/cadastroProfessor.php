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
                if($_SESSION["tipo"] == "professor"){
                    $id = $_SESSION["id"];
                }
                $datahoje = date("Y-m-d");
                ?>

                <table width="950px" align="center" id="tabelaprincipal">

                    <tr>
                        <td>
                            <div id="titulo">
                              
                              
                                <?php
                                if ($_SESSION["tipo"] == "administrador"){
                                ?>
                                <form method="post" target="_blank" action="acessosprofessores.php">
                                    <label>Data dos Acessos</label>
                                    <input type="date" name="txtData" value="<?php echo $datahoje?>">
                                    <input type="submit" value="Relatório de Acessos">
                                    
                                </form>
                                <hr>
                                <?php
                                }
                                if($_SESSION["tipo"] == "administrador") {
echo '<a href="definirCoordenador.php" class="button" style="float: left;">Professores Coordenadores</a>';
                                }
                                
                                ?>
                                
                                <h3>Cadastro de Professores</h3>
                              
                              <?php if ($_SESSION["tipo"] == "professor"): ?>
                                
                                <form method="post" action="alterarSenha.php">
                                  <input type="hidden" name="txtProfessor" value="<?= $id ?>">
                                <div align="center">
                                    <table id="cadastro">
                                        <tr>
                                            <td>Senha atual</td>
                                            <td><input type="password" name="txtSenha" placeholder="Digite sua senha atual"></td>
                                        </tr>
                                        <tr>
                                            <td>Nova senha</td>
                                            <td><input type="password" name="txtNovaSenha" placeholder="Digite sua nova senha">
                                              <br><input type='submit' value='Alterar'></td>
                                        </tr>
                                      </table>
                                  </div>
                                  </form>
                                  
                                <?php endif; ?>
                              
                            </div>
                            <?php
                            if($_SESSION["tipo"] != "professor"){
                            ?>
                            <form method="post" action="gravarProfessor.php">
                                <div align="center">
                                    <table id="cadastro">
                                        <tr>
                                            <td>
                                                Nome
                                            </td>
                                            <td>
                                                <input type="text" name="txtNome">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                E-mail
                                            </td>
                                            <td>
                                                <input type="text" name="txtEmail">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Senha
                                            </td>
                                            <td>
                                                <input type="password" name="txtSenha">
                                                <input type='submit' value='Gravar'>

                                            </td>
                                        </tr>




                                    </table>
                                </div>
                            </form>
                            <hr>
                            
                            <center>
                                <form method="get" action="cadastroProfessor.php">
                                    <b>Consultar Professores</b><input type="text" name="txtConsulta">
                                    <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
                                </form>
                            </center>

                            <?php
            }
                            include 'conexao.php';
                            $consulta = "";
                            if (isset($_GET["txtConsulta"])) {
                                $consulta = $_GET["txtConsulta"];
                            
                            if($_SESSION["tipo"] == "professor"){
                                $sql = "SELECT * FROM professor WHERE idProfessor = '$id' ORDER BY nome";
                            }else{
                            $sql = "SELECT * FROM professor WHERE nome LIKE '%$consulta%' ORDER BY nome";
                            }
                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);
                            if ($linhas > 0) {
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>Nome</td>
                <td>E-mail</td>
                <td>Senha</td>
                <td colspan='2'>Operações</td>
               
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idProfessor");
                                    $nome = mysql_result($resultados, $i, "nome");
                                    $email = mysql_result($resultados, $i, "email");
                                    
                                    echo "
                      <form method='post' action='operacaoProfessor.php'>
                      <tr>
                      <td>$id</td>
                      <td><input type='text' value='$nome' name='txtNome'></td>
                      <td><input type='text' value='$email' name='txtEmail'></td>
                      <td><input type='password' value='' name='txtSenha'></td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>
                      <form method='post' action='operacaoProfessor.php'>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='excluir'>
                      <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                      <td><a href='exibirAcessosProfessor.php?idProfessor=$id' target='_blank' class='botao' style='color:#FFF;'>Acessos</a></td>
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