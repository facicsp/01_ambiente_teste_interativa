<?php
session_start();
if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador") {
        
?>
<?php
    require 'database.php';
    if (isset($_POST["acao"]) && $_POST["acao"] == "enviado") {
        $id = $_POST["id"];
        $idioma = array(
            "nome" => $_POST["idioma"]
        );
        $gravar = DBUpDate('idioma', $idioma, "id = $id");
        echo "<script>location.href='editaidioma.php?id=$id'; alert('Atualizado com sucesso!');</script>";
    }

    if (isset($_GET["id"])) {
        $valor = DBRead("idioma", "WHERE id = ".$_GET["id"])[0];
    } else {
        echo "<script>location.href='listaidioma.php';</script>";
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
            <h1 class="titulo">Atualizar Idiomas</h1>
            <form action="#" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $valor['id'] ?>">
                <div class="form-group col-md-6">
                    <label for="idioma">Idioma</label>
                    <input name="idioma" value="<?= $valor['nome'] ?>" type="text" class="form-control" id="titulo" placeholder="Digite um Idioma">
                </div>
                <div class="form-group col-md-6" style="padding-top: 25px;">
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