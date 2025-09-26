<?php
session_start();
if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador") {
        
?>
<?php
    require 'database.php';
    if (isset($_POST["acao"]) && $_POST["acao"] == "enviado") {
        $classificacao = array(
            "classificacao" => $_POST["classificacao"],
            "codigo" => $_POST["codigo"],
            "instituicao" => $_POST["instituicao"]
        );
        $gravar = DBCreate('classificacao', $classificacao, true);
        echo "<script>alert('Cadastrado com sucesso!');</script>";
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
            <h1 class="titulo">Cadastro de Classificações</h1>
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="form-group col-md-6">
                    <label for="classificacao">Classificação</label>
                    <input name="classificacao" type="text" class="form-control" id="classificacao" placeholder="Digite um Classificação">
                </div>
                <div class="form-group col-md-6">
                    <label for="codigo">Código</label>
                    <input name="codigo" type="text" class="form-control" id="codigo" placeholder="Digite um Código">
                </div>
                <div class="form-group col-md-6">
                    <label for="instituicao">Instituição</label>
                    <input name="instituicao" type="text" class="form-control" id="instituicao" placeholder="Digite um Instituição">
                </div>
                <div class="form-group col-md-6" style="padding-top: 25px;">
                    <input type="hidden" name="acao" value="enviado">
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
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