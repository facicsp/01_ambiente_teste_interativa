<?php
require 'database.php';
$formulario = DBRead('formulario', null);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACIC - Repositório Acadêmico</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300|Material+Icons' rel='stylesheet' type='text/css'>
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            padding-bottom: 80px !important;
        }

        .titulo-pagina {
            font-weight: bold;
            margin: 40px 0 50px 0;
            font-size: 2.2em;
        }

        .breadcrumb {
            margin-top: 20px;
        }

        .table-responsive {
            border: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">FACIC</li>
                    <li class="breadcrumb-item">Repositório Acadêmico</li>
                    <li class="breadcrumb-item active" aria-current="page">Trabalhos</li>
                </ol>
            </nav>

            <h1 class="titulo-pagina">Lista de Trabalhos</h1>

            <div class="table-responsive">
                <table class="display" id="table">
                    <?php
                    if ($formulario) {
                        echo '<thead>
                            <tr>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Data de Apresentação</th>
                                <th>Documento</th>
                            </tr>
                        </thead>
                        <tbody>';
                        foreach ($formulario as $form) {
                            echo "<tr>
                                <td>{$form['titulo']}</td>
                                <td>{$form['autor']}</td>
                                <td>" . (date_format(date_create($form['data_apresentacao']), "d/m/Y")) . "</td>
                                <td><a target='_blank' href='repositorio/{$form['arquivo']}'>Baixar</td>
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
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

    </script>
    <script>
        $(document).ready(function () {
            $('#table').dataTable({
                language: {
                    url: 'pt_br.json'
                }
            });
        });
    </script>
</body>

</html>