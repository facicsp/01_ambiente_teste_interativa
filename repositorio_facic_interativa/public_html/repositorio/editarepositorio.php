<?php
session_start();
if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador") {
        
?>
<?php
    require 'database.php';
    if (isset($_POST["acao"]) && $_POST["acao"] == "enviado") {
        $id = $_POST["id"];
        $formulario = array(
            "titulo" => $_POST["titulo"],
            "subtitulo" => $_POST["subtitulo"],
            "autor" => $_POST["autor"],
            "id_orientador" => $_POST["id_orientador"],
            "id_coorientador" => $_POST["id_coorientador"],
            "id_classificacao" => $_POST["id_classificacao"],
            "data_apresentacao" => $_POST["data_apresentacao"],
            "instituicao" => $_POST["instituicao"],
            "formacao" => $_POST["formacao"],
            "idioma" => $_POST["idioma"],
            "resumo" => $_POST["resumo"],
            "abstract" => $_POST["abstract"],
            "palavras_chave" => $_POST["palavras_chave"],
            "banca_presidente" => $_POST["banca_presidente"],
            "banca_membro1" => $_POST["banca_membro1"],
            "banca_membro2" => $_POST["banca_membro2"]
        );
        if(isset($_FILES['arquivo'])) {
            date_default_timezone_set("Brazil/East");
            $ext = strtolower(substr($_FILES['arquivo']['name'],-4));
            $new_name = md5(date("Y.m.d-H.i.s")) . $ext;
            $dir = 'repositorio/';
            move_uploaded_file($_FILES['arquivo']['tmp_name'], $dir.$new_name);
            $formulario["arquivo"] = $new_name;
        }
        $gravar = DBUpDate('formulario', $formulario, "id = $id");
        echo "<script>location.href='editarepositorio.php?id=$id'; alert('Atualizado com sucesso!');</script>";
    }

    if (isset($_GET["id"])) {
        $valor = DBRead("formulario", "WHERE id = ".$_GET["id"])[0];
    } else {
        echo "<script>location.href='listarepositorio.php';</script>";
    }

    $docente = DBRead("professor", null);

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
            <h1 class="titulo">Atualizar Trabalho</h1>
            <form action="#" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $_GET["id"]; ?>">
                <div class="form-group col-md-4">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control" value="<?= $valor['titulo'] ?>" id="titulo" placeholder="Digite um Titulo">
                </div>
                <div class="form-group col-md-4">
                    <label for="subtitulo">Subtitulo</label>
                    <input name="subtitulo" type="text" class="form-control" value="<?= $valor['subtitulo'] ?>" id="subtitulo" placeholder="Digite um Subtitulo">
                </div>
                <div class="form-group col-md-4">
                    <label for="autor">Autor</label>
                    <input name="autor" type="text" class="form-control" value="<?= $valor['autor'] ?>" id="autor" placeholder="Digite um Autor">
                </div>
                <div class="form-group col-md-4">
                    <label for="id_orientador">Orientador</label>
                    <select class="form-control" id="id_orientador" name="id_orientador">
                        <?php
                        if ($docente) {
                            foreach ($docente as $d) {
                                if ($valor['id_orientador'] == $d['idProfessor']) echo "<option selected value='".$d['idProfessor']."'>".$d['nome']."</option>";
                                else  echo "<option value='".$d['idProfessor']."'>".$d['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="id_coorientador">Co-orientador</label>
                    <select class="form-control" id="id_coorientador" name="id_coorientador">
                        <?php
                        if ($docente) {
                            foreach ($docente as $d) {
                                if ($valor['id_coorientador'] == $d['idProfessor']) echo "<option selected value='".$d['idProfessor']."'>".$d['nome']."</option>";
                                else  echo "<option value='".$d['idProfessor']."'>".$d['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="id_classificacao">Classificacao</label>
                    <select class="form-control" value="<?= $valor['id_classificacao'] ?>" id="id_classificacao" name="id_classificacao">
                        <?php
                            $classificacao = DBRead("classificacao", null);
                            if ($classificacao) {
                                foreach ($classificacao as $c) {
                                    echo "<option value='".$c['id']."'>".$c['classificacao']."</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="data">Data de apresentação</label>
                    <input type="date" name="data_apresentacao" class="form-control" value="<?= $valor['data_apresentacao'] ?>" id="data" placeholder="Digite um Data">
                </div>
                <div class="form-group col-md-4">
                    <label for="instituicao">Instituição</label>
                    <select name="instituicao" class="form-control" id="instituicao">
                        <?php
                            $instituicoes = DBRead("instituicoes", null);
                            if ($instituicoes) {
                                foreach ($instituicoes as $i) {
                                    if ($i['id'] == $valor['instituicao']) echo "<option value='".$i['id']."'>".$i['sigla'].' - '.$i['nome']."</option>";
                                    else echo "<option value='".$i['id']."'>".$i['sigla'].' - '.$i['nome']."</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="formacao">Formação</label>
                    <input name="formacao" type="text" class="form-control" value="<?= $valor['formacao'] ?>" id="formacao" placeholder="Digite um Formação">
                </div>
                <div class="form-group col-md-4">
                    <label for="idioma">Idioma</label>
                    <select class="form-control" id="idioma" name="idioma">
                        <?php
                            $idioma = DBRead("idioma", null);
                            if ($idioma) {
                                foreach ($idioma as $i) {
                                    if ($i['id'] == $valor['idioma']) echo "<option selected value='".$i['id']."'>".$i['nome']."</option>";
                                    else echo "<option value='".$i['id']."'>".$i['nome']."</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="palavras_chave">Palavra chave</label>
                    <input type="text" class="form-control" value="<?= $valor['palavras_chave'] ?>" id="palavras_chave" placeholder="Digite um Palavra chave" name="palavras_chave">
                </div>
                <div class="form-group col-md-4">
                    <label for="banca_presidente">Presidente da banca</label>
                    <select class="form-control" value="<?= $valor['banca_presidente'] ?>" id="banca_presidente" name="banca_presidente">
                        <?php
                        if ($docente) {
                            foreach ($docente as $d) {
                                if ($valor['banca_presidente'] == $d['idProfessor']) echo "<option selected value='".$d['idProfessor']."'>".$d['nome']."</option>";
                                else  echo "<option value='".$d['idProfessor']."'>".$d['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="banca_membro1">Membro 1</label>
                    <select class="form-control" value="<?= $valor['banca_membro1'] ?>" id="banca_membro1" name="banca_membro1">
                        <?php
                        if ($docente) {
                            foreach ($docente as $d) {
                                if ($valor['banca_membro1'] == $d['idProfessor']) echo "<option selected value='".$d['idProfessor']."'>".$d['nome']."</option>";
                                else  echo "<option value='".$d['idProfessor']."'>".$d['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="banca_membro2">Membro 2</label>
                    <select class="form-control" value="<?= $valor['banca_membro2'] ?>" id="banca_membro2" name="banca_membro2">
                        <?php
                        if ($docente) {
                            foreach ($docente as $d) {
                                if ($valor['banca_membro2'] == $d['idProfessor']) echo "<option selected value='".$d['idProfessor']."'>".$d['nome']."</option>";
                                else  echo "<option value='".$d['idProfessor']."'>".$d['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="arquivo">Anexar arquivo</label>
                    <input name="arquivo" type="file" class="form-control" value="<?= $valor['arquivo'] ?>" id="arquivo">
                </div>
                <div class="form-group col-md-6">
                    <label for="resumo">Resumo</label>
                    <textarea name="resumo" class="form-control" id="resumo" rows="3" placeholder="Digite um Resumo"><?= $valor['resumo'] ?></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label for="abstract">Abstract</label>
                    <textarea name="abstract" class="form-control" id="abstract" rows="3" placeholder="Digite um Abstract"><?= $valor['abstract'] ?></textarea>
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