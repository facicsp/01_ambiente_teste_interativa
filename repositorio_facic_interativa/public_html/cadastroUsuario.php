<?php
session_start();
include "conexao.php";
$seguranca = new Seguranca();

if (isset($_GET["ra"])) {
    $ra = $seguranca->antisql($_GET["ra"]);
    $result = mysql_query("SELECT ra FROM usuario WHERE ra = '$ra'");
    echo mysql_num_rows($result) > 0 ? "1" : "0";
    exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>

    <link rel="stylesheet" href="css/cadastro.css">
    <script src="js/jquery.min.js"></script>

    <script>
        function verificaRA(el) {
            $("input[name='txtSenha']").val(el.value);

            $.ajax({
                url: "cadastroUsuario.php?ra=" + el.value, success: function (result) {
                    $('#ra')[0].innerHTML = "";

                    if (result == "1") {
                        $('#ra')[0].innerHTML = "já cadastrado";
                    }
                }
            });
        }
    </script>
</head>


<body>
    <?php

    if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "administrador") {
            //conteudo do site
            include "topo.php";

            ?>

            <table width="950px" align="center" id="tabelaprincipal">

                <tr>
                    <td>
                        <div id="titulo">
                            <h3>Cadastro de Usuários</h3>
                        </div>
                        <form method="post" action="gravarUsuario.php">
                            <div align="center">
                                <table id="cadastro">
                                    <tr>
                                        <td>
                                            Adaptado
                                        </td>
                                        <td>
                                            <select name="txtAdaptado">
                                                <option value="Nao">Não</option>
                                                <option value="Sim">Sim</option>
                                            </select>


                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            RA <b style="color: red;" id="ra"></b>
                                        </td>
                                        <td>
                                            <input type="text" size="20" name="txtRa" onblur="verificaRA(this)">


                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Nome
                                        </td>
                                        <td>
                                            <input type="text" size="40" maxlength="100" name="txtNome">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Nome Social
                                        </td>
                                        <td>
                                            <input type="text" size="40" maxlength="100" name="txtNomeSocial">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Endereço
                                        </td>
                                        <td>
                                            <input type="text" size="40" maxlength="40" name="txtEndereco">



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Bairro
                                        </td>
                                        <td>
                                            <input type="text" size="40" maxlength="20" name="txtBairro">



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Cidade
                                        </td>
                                        <td>
                                            <input type="text" size="40" maxlength="20" name="txtCidade">



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Estado
                                        </td>
                                        <td>
                                            <select name='txtEstado'>
                                                <option value='AC'>Acre</option>
                                                <option value='AL'>Alagoas</option>
                                                <option value='AP'>Amapá</option>
                                                <option value='AM'>Amazonas</option>
                                                <option value='BA'>Bahia</option>
                                                <option value='CE'>Ceará</option>
                                                <option value='DF'>Distrito Federal</option>
                                                <option value='ES'>Espirito Santo</option>
                                                <option value='GO'>Goiás</option>
                                                <option value='MA'>Maranhão</option>
                                                <option value='MS'>Mato Grosso do Sul</option>
                                                <option value='MT'>Mato Grosso</option>
                                                <option value='MG'>Minas Gerais</option>
                                                <option value='PA'>Pará</option>
                                                <option value='PB'>Paraíba</option>
                                                <option value='PR'>Paraná</option>
                                                <option value='PE'>Pernambuco</option>
                                                <option value='PI'>Piauí</option>
                                                <option value='RJ'>Rio de Janeiro</option>
                                                <option value='RN'>Rio Grande do Norte</option>
                                                <option value='RS'>Rio Grande do Sul</option>
                                                <option value='RO'>Rondônia</option>
                                                <option value='RR'>Roraima</option>
                                                <option value='SC'>Santa Catarina</option>
                                                <option value='SP'>São Paulo</option>
                                                <option value='SE'>Sergipe</option>
                                                <option value='TO'>Tocantins</option>
                                            </select>



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            CEP
                                        </td>
                                        <td>
                                            <input type="text" size="20" maxlength="12" name="txtCep">



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Nascimento
                                        </td>
                                        <td>
                                            <input type="date" size="20" name="txtNascimento">



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Telefone
                                        </td>
                                        <td>
                                            <input type="text" size="20" maxlength="13" name="txtTelefone">



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Celular
                                        </td>
                                        <td>
                                            <input type="text" size="20" maxlength="13" name="txtCelular">



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            E-mail
                                        </td>
                                        <td>
                                            <input type="text" size="40" maxlength="60" name="txtEmail">



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Senha
                                        </td>
                                        <td>
                                            <input type="text" size="20" maxlength="32" name="txtSenha">



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Tipo
                                        </td>
                                        <td>
                                            <select name="txtTipo">
                                                <option value="aluno">Aluno</option>
                                                <option value="administrador">Administrador</option>
                                            </select>



                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Turma
                                        </td>
                                        <td>
                                            <select name="txtTurma">
                                                <option value="0">::Escolha uma turma::</option>
                                                <?php
                                                $sql = "SELECT * FROM turma WHERE semestre = '" . $_SESSION['semestre'] . "' ORDER BY turma";
                                                echo $sql;
                                                $resultados = mysql_query($sql);
                                                $linhas = mysql_num_rows($resultados);
                                                if ($linhas > 0) {
                                                    for ($i = 0; $i < $linhas; $i++) {
                                                        $idTurma = mysql_result($resultados, $i, "idTurma");
                                                        $turma = mysql_result($resultados, $i, "turma");
                                                        echo "<option value='$idTurma'>$turma</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                    </tr>
                                    <tr>
                                        <td>
                                            Observações
                                        </td>
                                        <td>
                                            <textarea name="txtObservacoes" rows="4" cols="30" maxlength="200"></textarea>

                                            <input type='image' name='img_gravar' src='imagens/gravar.png' border='0'
                                                title='Gravar'>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                        <hr>
                        <center>
                            <form method="get" action="cadastroUsuario.php">
                                <b>Consultar Alunos</b><input type="text" name="txtConsulta">
                                <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0'
                                    title='Pesquisar'>
                            </form>
                        </center>

                        <?php
                        $consulta = "";
                        if (isset($_GET["txtConsulta"])) {
                            $consulta = $seguranca->antisql($_GET["txtConsulta"]);

                            $condicao = "nome LIKE '%$consulta%'";
                            if (is_numeric($consulta))
                                $condicao = "ra = '$consulta'";

                            $sql = "SELECT idUsuario,nome,tipo,ra FROM usuario WHERE $condicao LIMIT 1000";
                            //echo $sql;
                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);
                            if ($linhas > 0) {
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Código</td>
                <td>RA</td>
                <td>Nome</td>
                <td colspan='3'>Operações</td>
                </tr>
";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $id = mysql_result($resultados, $i, "idusuario");
                                    $nome = mysql_result($resultados, $i, "nome");
                                    $tipo = mysql_result($resultados, $i, "tipo");
                                    $ra = mysql_result($resultados, $i, "ra");
                                    echo "
                      <form method='post' action='alterarUsuario.php'>
                      <tr>
                      <td>$id</td>
                      <td>$ra</td>
                      <td>$nome</td>
                      <input type='hidden' name='id' value='$id'>
                      <input type='hidden' name='operacao' value='alterar'>
                      <td><input type='image' name='img_atualizar' src='imagens/atualizar.png' border='0' title='Atualizar'></td>
                      </form>";
                                    if ($tipo != "administrador") {
                                        echo "<form method='post' action='historicoNotas.php?id=$id'>
                          <td><input type='submit' value='Notas'></td>
                          </form>";

                                        echo "<form method='post' action='operacaoUsuario.php'>
                          <input type='hidden' name='id' value='$id'>
                          <input type='hidden' name='operacao' value='excluir'>
                          <td><input type='image' name='img_atualizar' src='imagens/remover.png' border='0' title='Remover'></td>
                          </form>";
                                    }

                                    echo "</tr>
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