<?php
session_start();
if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador") {
        
?>
<?php
    require 'database.php';
    if (isset($_POST["acao"]) && $_POST["acao"] == "enviado") {
        $id = $_POST["id"];
        $instituicoes = array(
            "nome" => $_POST["nome"],
            "sigla" => $_POST["sigla"]
        );
        $gravar = DBUpDate('instituicoes', $instituicoes, true);
        echo "<script>location.href='editainstituicao.php?id=$id'; alert('Atualizado com sucesso!');</script>";
    }

    if (isset($_GET["id"])) {
        $valor = DBRead("instituicoes", "WHERE id = ".$_GET["id"])[0];
    } else {
        echo "<script>location.href='listainstituicao.php';</script>";
    }

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACIC</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
    	<div class="row">
            <h1 class="titulo">Atualizar Instituições</h1>
            <form action="#" method="post">
                <input type="hidden" name="id" value="<?= $valor['id'] ?>">
                <div class="form-group col-md-6">
                    <label for="nome">Instituição</label>
                    <input name="nome" value="<?= $valor['nome'] ?>" type="text" class="form-control" id="nome" placeholder="Digite a Instituição">
                </div>
                <div class="form-group col-md-6">
                    <label for="sigla">Sigla</label>
                    <input name="sigla" value="<?= $valor['sigla'] ?>" type="text" class="form-control" id="sigla" placeholder="Digite a Sigla">
                </div>
                <div class="form-group col-md-6">
                    <input type="hidden" name="acao" value="enviado">
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
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