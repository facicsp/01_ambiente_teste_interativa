<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title>FACIC INTERATIVA - BIBLIOTECAS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/cadastro.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <style>
        .list-bibliotecas img {
            width: 200px;
            height: 60px;
            object-fit: contain;
            margin: 20px 10px;
        }

        .list-bibliotecas img:hover {
            opacity: .8;
            transition: .3s;
        }

        .area {
            background-color: #069;
            color: #FFF;
            font-size: 16px;
            margin: 10px 10px 0 10px;
            padding: 4px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .none {
            display: none !important;
        }

        div#periodicos {
            display: flex;
            width: 100%;
            flex-wrap: wrap;
        }

        .periodico {
            width: 100%;
            margin: 4px;
            text-align: left;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            display: block; /* Garante que funcione corretamente */
            white-space: nowrap; /* Impede que os conteúdos quebrem */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px; /* Evita que a tabela fique muito pequena */
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION["usuario"])) {
        if ($_SESSION["tipo"] == "aluno" || $_SESSION["tipo"] == "professor") {
            //conteudo do site
            include "topo.php";
            $idAluno = $_SESSION["usuario"];

            ?>
            <div class="bibliotecas">
                <div class="list-bibliotecas" style="width: 100%;">

                    <!-- biblioteca.html -->
                    <a href="https://plataforma.bvirtual.com.br/Account/Login?redirectUrl=%2F" target="_blank">
                        <img src="imagens/pearson.png">
                    </a>

                    <a href="gerarTokenSaraiva.php?cod=FASCINTERATIVA<?= $idAluno ?>" target="_blank">
                        <img src="imagens/logo-saraiva.PNG">
                    </a>

                    <!-- minhabiblioteca.php -->
                    <a href="https://www.google.com/url?q=https://dliportal.zbra.com.br/Login.aspx?key%3DFASC&sa=D&source=apps-viewer-frontend&ust=1735346164602927&usg=AOvVaw2BLW8UyVO9FNEeqQmfhFez&hl=pt-BR"
                        target="_blank">
                        <img src="imagens/minhabiblioteca.svg">
                    </a>

                </div>
            </div>
            <hr>

            <div class="container" style="margin-bottom: 50px">

                <div class="aba" onclick="trocaraba(1)">Periódicos</div>
                <div class="aba" onclick="trocaraba(2)">Repositório</div>

                <div id="periodicos" style="padding-bottom: 100px">
                    <?php
                    include 'LoginRestrito/conexao.php';

                    $sql = "SELECT p.*,a.area from periodico p,area a WHERE p.idarea = a.idarea ORDER BY a.area,p.titulo";
                    $result = mysqli_query($conexao, $sql);
                    $linhas = mysqli_num_rows($result);

                    if ($linhas > 0) {

                        $areaauxiliar = "";

                        for ($i = 0; $i < $linhas; $i++) {
                            $area = mysql_result($result, $i, "area");

                            if ($area != $areaauxiliar) {
                                echo "<div class='area' id='area-$area'>$area</div>";
                            }

                            $areaauxiliar = $area;
                        }

                        for ($i = 0; $i < $linhas; $i++) {
                            $titulo = mysql_result($result, $i, "titulo");
                            $link = mysql_result($result, $i, "link");
                            $area = mysql_result($result, $i, "area");
                            echo "<p class='periodico area-$area none'><a href='$link' target='_blank'>- $titulo</a></p>";
                        }

                    } else {
                        echo "<p>Nenhum item encontrado.</p>";
                    }

                    echo "</div>";


                    echo "<div id='repositorio' style='display:none;float:left;width:100%; padding-bottom: 100px'>";
                    //Exibir os repositórios
                    mysqli_query($conexao, "SET NAMES 'utf8'");
                    mysqli_query($conexao, 'SET character_set_connection=utf8');
                    mysqli_query($conexao, 'SET character_set_client=utf8');
                    mysqli_query($conexao, 'SET character_set_results=utf8');
                    $sql = "SELECT f.id, titulo, subtitulo, autor, arquivo, classificacao, i.nome AS instituicao from formulario f
LEFT JOIN classificacao c ON c.id = f.id_classificacao 
LEFT JOIN instituicoes i ON i.id = f.instituicao 
ORDER BY titulo";
                    $result = mysqli_query($conexao, $sql);
                    $linhas = mysqli_num_rows($result);
                    $areaauxiliar = "";
                    if ($linhas > 0) {

                        echo '<div class="table-container"><table id="table" style="margin-top: 30px;">
                        <thead>
        <tr>
            <th>#</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Classificação</th>
            <th>Insituição</th>
            <th>Documento</th>
        </tr></thead><tbody>';

                        for ($i = 0; $i < $linhas; $i++) {
                            $id = mysql_result($result, $i, "id");
                            $titulo = mysql_result($result, $i, "titulo");
                            $subtitulo = mysql_result($result, $i, "subtitulo");
                            $autor = mysql_result($result, $i, "autor");
                            $arquivo = mysql_result($result, $i, "arquivo");
                            $classificacao = mysql_result($result, $i, "classificacao");
                            $instituicao = mysql_result($result, $i, "instituicao");



                            echo "<tr>
            <td>$id</td>
            <td>$titulo<br>$subtitulo</td>
            <td>$autor</td>
            <td>$classificacao</td>
            <td>$instituicao</td>
            <td>
                <a href='repositorio/repositorio/$arquivo' target='_blank'>Baixar</a>
            </td>
        </tr>";
                            $areaauxiliar = $area;
                        }

                        echo '</tbody></table></div>';
                    } else {
                        echo "<p>Nenhum item encontrado.</p>";
                    }

                    echo "</div></div>";


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
        </div>
    </div>

    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <script>
        function trocaraba(id) {
            if (id == 1) {
                document.getElementById("periodicos").style.display = "flex";
                document.getElementById("repositorio").style.display = "none";
            } else if (id == 2) {
                document.getElementById("periodicos").style.display = "none";
                document.getElementById("repositorio").style.display = "flex";
            }
        }

        $(document).ready(function () {
            $('.area').click(function (e) {
                $('.periodico').addClass('none');
                $('.' + e.target.id).removeClass('none');
            });

            $('#table').dataTable({
                lengthChange: false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
                }
            });
        });
    </script>



</body>

</html>