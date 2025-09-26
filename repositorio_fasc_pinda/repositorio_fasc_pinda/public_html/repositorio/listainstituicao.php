<?php
session_start();
if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador") {
        
?>
<?php
    require 'database.php';
    $instituicoes = DBRead('instituicoes', null);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACIC</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300|Material+Icons' rel='stylesheet' type='text/css'>
	<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
    	<div class="row">
            <h1 class="titulo">Lista de Instituições</h1>
            <table class="display" id="table">
                <?php
                    if ($instituicoes) {
                        echo '<thead><tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Sigla</th>
                            <th>Editar</th>
                            <th>Editar</th>
                            <th>Excluir</th>
                        </tr></thead><tbody>';
                        foreach ($instituicoes as $i) {
                            echo "<tr>
                                <th>{$i['id']}</th>
                                <th>{$i['nome']}</th>
                                <th>{$i['sigla']}</th>
                                <td class='text-center'>
                                    <a href='editainstituicao.php?id={$i['id']}' class='btn btn-warning btn-action'>
                                        <i class='material-icons'>edit</i>
                                    </a>
                                </td>
                                <td class='text-center'>
                                    <a href='excluir.php?table=instituicoes&redirect=listainstituicao&id={$i['id']}' class='btn btn-danger btn-action'>
                                        <i class='material-icons'>delete</i>
                                    </a>
                                </td>
                            </tr>";
                        }
                        echo "</tbody>";
                    } else {
                        echo "<p>Ops! Nenhum registro encontrado.</p>";
                    }
                ?>
            </table>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

    </script>
	<script>
	    $(document).ready(function() {
            $('#table').dataTable( {
                "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
            }
        } );
	    });
	</script>
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