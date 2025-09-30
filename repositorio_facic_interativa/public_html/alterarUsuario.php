<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/cadastro.css">
</head>

<body>
    <?php
    if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "aluno") {
            //conteudo do site
            include "topo.php";
            include "LoginRestrito/conexao.php";
            $seguranca = new Seguranca();
            if ($_SESSION["tipo"] == "administrador") {
                $idUsuario = $seguranca->antisql($_POST["id"]);
            } else if ($_SESSION["tipo"] == "aluno") {
                $idUsuario = $_SESSION["id"];
            }
            $sql = "SELECT * FROM usuario WHERE idusuario = '$idUsuario'";
            $resultados = mysqli_query($conexao, $sql);
            $linhas = mysqli_num_rows($resultados);
            if ($linhas > 0) {

                for ($i = 0; $i < $linhas; $i++) {
                    $ra = mysql_result($resultados, $i, "ra");
                    $endereco = mysql_result($resultados, $i, "endereco");
                    $bairro = mysql_result($resultados, $i, "bairro");
                    $cidade = mysql_result($resultados, $i, "cidade");
                    $estado = mysql_result($resultados, $i, "estado");
                    $cep = mysql_result($resultados, $i, "cep");
                    $nascimento = mysql_result($resultados, $i, "nascimento");
                    $telefone = mysql_result($resultados, $i, "telefone");
                    $celular = mysql_result($resultados, $i, "celular");
                    $email = mysql_result($resultados, $i, "email");
                    $tipo = mysql_result($resultados, $i, "tipo");
                    $observacoes = mysql_result($resultados, $i, "observacoes");
                    $adaptado = mysql_result($resultados, $i, "adaptado");

                    $nomeCivil = mysql_result($resultados, $i, "nome");
                    $nomeSocial = mysql_result($resultados, $i, "nomesocial");

                    if (trim($nomeSocial) !== "") {
                        $temp = $nomeCivil;
                        $nomeCivil = $nomeSocial;
                        $nomeSocial = $temp;
                    }
                }
            }
            ?>



            <div id="titulo" class="grid-100">
                <h3>Alterar Usuário</h3>
            </div>
            <div class="formulario grid-80 prefix-10 suffix-10">
                <form method="post" action="operacaoUsuario.php">
                    <?php
                    echo "<input type='hidden' name='id' value='$idUsuario'>";
                    echo "<input type='hidden' name='operacao' value='alterar'>";
                    if ($_SESSION["tipo"] == "administrador") {
                        ?>
                        <div class="grid-100">
                            <label>Adaptado</label>
                        </div>
                        <div class="grid-100">
                            <select name="txtAdaptado">

                                <option value="<?php echo $adaptado; ?>"><?php echo $adaptado; ?></option>
                                <option value="Nao">Não</option>
                                <option value="Sim">Sim</option>
                            </select>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="grid-100">
                        <label>RA</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" size="20" name="txtRa" value="<?php echo $ra; ?>">

                    </div>
                    <div class="grid-100">
                        <label>Nome Civil</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" size="40" name="txtNome" value="<?php echo $nomeCivil; ?>">
                    </div>
                    <div class="grid-100">
                        <label>Nome Social</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" size="40" name="txtNomeSocial" value="<?php echo $nomeSocial; ?>">
                    </div>
                    <div class="grid-100">
                        <label>Endereço</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" size="40" name="txtEndereco" value="<?php echo $endereco; ?>">

                    </div>
                    <div class="grid-100">
                        <label>Bairro</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" size="40" name="txtBairro" value="<?php echo $bairro; ?>">


                    </div>
                    <div class="grid-100">
                        <label>Cidade</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" size="40" name="txtCidade" value="<?php echo $cidade; ?>">

                    </div>
                    <div class="grid-100">
                        <label>Estado</label>
                    </div>
                    <div class="grid-100">
                        <select name='txtEstado'>
                            <?php echo "<option value='$estado'>$estado</option>"; ?>
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


                    </div>
                    <div class="grid-100">
                        <label>CEP</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" size="20" name="txtCep" value="<?php echo $cep; ?>">

                    </div>
                    <div class="grid-100">
                        <label>Nascimento</label>
                    </div>
                    <div class="grid-100">
                        <input type="date" size="20" name="txtNascimento" value="<?php echo $nascimento; ?>">


                    </div>
                    <div class="grid-100">
                        <label>Telefone</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" size="20" name="txtTelefone" value="<?php echo $telefone; ?>">

                    </div>
                    <div class="grid-100">
                        <label>Celular</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" size="20" name="txtCelular" value="<?php echo $celular; ?>">


                    </div>
                    <div class="grid-100">
                        <label>E-mail</label>
                    </div>
                    <div class="grid-100">
                        <input type="text" size="40" name="txtEmail" value="<?php echo $email; ?>">


                    </div>
                    <div class="grid-100">
                        <label>Senha</label>
                    </div>
                    <div class="grid-100">
                        <input type="password" size="20" name="txtSenha"><span>Deixe vazio, caso não queira alterar.</span>


                    </div>
                    <div class="grid-100">
                        <label>Observações</label>
                    </div>
                    <div class="grid-100">
                        <textarea name="txtObservacoes" rows="4" cols="30"><?php echo $observacoes; ?></textarea>

                        <input type='submit' name='img_gravar' value="Alterar">


                    </div>
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
    } else {
        echo "<script>"
            . "alert('É necessário fazer o login!');"
            . "window.location='login.html';"
            . "</script>";
    }
    ?>