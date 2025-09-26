<?php
session_start();

 
$tipos = [
    // "turma" => [
    //     "nome" => "Minha turma",
    //     "descricao" => "Bem-vindo ao NOME_DA_FUNCIONALIDADE, todas as mensagens enviadas aqui estarão disponíveis para todos os alunos da sua turma."
    // ],
    "curso" => [
        "nome" => "Meu curso",
        "descricao" => "Bem-vindo ao NOME_DA_FUNCIONALIDADE, todas as mensagens enviadas aqui estarão disponíveis para todas as turmas do seu curso."
    ]//,
    // "professor" => [
    //     "nome" => "Meu professor",
    //     "descricao" => "Bem-vindo ao NOME_DA_FUNCIONALIDADE, todas as mensagens enviadas aqui estarão disponíveis para seu professor."
    // ]
];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>FACIC - Comunicação</title>
    <link rel="stylesheet" href="css/cadastro.css">
    <style>
    /* ===== Scrollbar CSS ===== */
    /* Firefox */
    * {
        scrollbar-width: auto;
        scrollbar-color: #adadad #ffffff;
    }

    /* Chrome, Edge, and Safari */
    *::-webkit-scrollbar {
        width: 12px;
    }

    *::-webkit-scrollbar-track {
        background: #ffffff;
    }

    *::-webkit-scrollbar-thumb {
        background-color: #adadad;
        border-radius: 6px;
        border: 2px solid #ffffff;
    }


    html,
    body,
    .conteudos,
    .topicos {
        max-height: 100vh !important;
    }

    .date {
        color: #1a8c43 !important;
        background-color: transparent !important;
        font-size: 1em !important;
        font-weight: 600 !important;
    }

    .conteudos {
        background-color: #eaeaea;
        border-left: 1px solid #D3D3D3;
        position: fixed;
        width: 75%;
        margin-left: 25%;
        height: calc(100% - 100px);
        min-height: calc(100% - 100px);

    }

    .topicos {
        height: calc(100% - 50px);
        min-height: calc(100% - 50px);
        overflow-y: scroll;
    }

    .descricao {
        display: none;
        padding: 15px;
        background-color: #cdcbcb;
        color: #544747 !important;
    }

    .comentario {}

    .oculto {
        display: none;
        font-weight: normal;
        color: #000;
    }

    .title {
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-bottom: 1px solid #DDD;
    }

    .escrever {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #FFF;
        position: fixed;
        width: inherit;
        bottom: 0;
        padding: 15px;
    }

    .conversa {
        overflow: scroll;
        height: calc(100vh - 150px);
        padding-bottom: 75px;
        display: flex;
        justify-content: center;
        flex-direction: column;
        list-style: none;
        margin-top: 0;
    }

    .conversa li {
        width: fit-content;
        background-color: #f9f9f9;
        border-radius: 15px;
        margin: 7px 20px;
        font-size: 1.1em;
        padding: 15px 20px !important;
        text-align: left;
    }

    .conversa li span {
        font-size: 1em;
        margin: 0;
        background-color: transparent;
        font-weight: bold;
    }

    .mensagem-professor span {
        color: #43a047;
    }

    #professor {
        height: auto;
        font-size: 1.1em;
        width: auto;
        margin-right: 20px;
        padding: 5px 10px;
    }

    .anexo-foto {
        width: 200px;
        height: 200px;
    }

    .anexo-foto img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .anexo {
        margin-top: 10px;
    }

    .anexo a {
        color: #1976d2;
        font-weight: bold;
    }
    </style>

    <script>
    function expandir(el, id) {
        document.getElementById(id).style.display = "contents";
        el.style.display = "none";
    }
    </script>


</head>

<body>

    <?php
        
        include "topo.php";
        ?>
    <div class="caixa" id="caixa">

    </div>
    <div class='topicos'>


        <?php
            if (isset($_SESSION["usuario"])) {
                if ($_SESSION["tipo"] == "aluno" || $_SESSION["tipo"] == "professor") {

                    include './conexao.php';
                    $seguranca = new Seguranca();
                    $idEscritor = $seguranca->antisql($_SESSION["id"]);
                    $tipoEscritor = $seguranca->antisql($_SESSION["tipo"]);
                    $semestre = $seguranca->antisql($_SESSION["semestre"]);
                    $tipo = $seguranca->antisql($_GET["type"]); 
                    $selectProfessor = "";

                    if ($tipoEscritor == "aluno" && $tipo == "professor") {
                    
                    $sql = "select disciplina.*,professor.nome from disciplina,professor
                    where idturma in(
                    SELECT turma.idTurma FROM matricula
                    LEFT JOIN turma ON turma.idTurma = matricula.idTurma
                    WHERE idaluno = '$idEscritor' AND ativo = 'sim'
                    )
                    AND disciplina.idProfessor = professor.idProfessor AND semestre = '$semestre'";

                        $result = mysql_query($sql);
                        $linhas = mysql_num_rows($result);
                        
                        if ($linhas > 0) {
                            $selectProfessor = "<select id='professor'>";
                            $selectProfessor .= "<option value='' selected disabled>::Selecione um professor::</option>";
                            for ($i = 0; $i < $linhas; $i++) {
                                $idProfessor = mysql_result($result, $i, "idProfessor");
                                $nome = mysql_result($result, $i, "nome");
                                $selectProfessor .= "<option value='{$idProfessor}'>{$nome}</option>";
                            }
                            $selectProfessor .= "</select>";
                        }
                    }

                    ?>

        <div class='title'><a href='index.php' style='color:#069;'>Voltar</a></div>
        <h2>:::Seleciona uma opção:::</h2>

        <?php
        
        if ($tipoEscritor == "aluno") {
            foreach ($tipos as $key => $value) {
                echo "<a href='comunicacao.php?type={$key}' style='text-decoration:none;'>
                        <div class='titulotopico'>
                            <p>{$value["nome"]}</p>
                        </div>
                    </a>";
            }
        } else {
            include './funcaoDisciplinas.php';

            // mensagens dos cursos
            $sql = sqlTurma($idEscritor, $semestre);
            $result = mysql_query($sql);
            $linhas = mysql_num_rows($result);
            $idsCursoProfessor = [];

            if ($linhas > 0) {
                for ($i=0; $i < $linhas; $i++) { 
                    $idCurso = mysql_result($result, $i, "idCurso");
                    
                    $res = mysql_query("SELECT * FROM curso WHERE idCurso = '$idCurso'");
                    if (mysql_num_rows($res) > 0) {
                        $idCurso = mysql_result($res, 0, "idCurso");
                        $curso = mysql_result($res, 0, "descricao");

                        if (!in_array($idCurso, $idsCursoProfessor)) {
                            $idsCursoProfessor[] = $idCurso;

                            echo "<div class='titulotopico'>
                                    <p><a href='comunicacao.php?type=curso&curso={$idCurso}' style='text-decoration:none;'>{$curso}</a></p>
                                </div>";
                        }
                    }
                }
            } else {
                echo "Nenhuma turma encontrada.";
            }

            // mensagens das turmas
            // echo "<div class='titulotopico'>
            //         <p>Mensagens das turmas</p>
            //     </div>";

            // $sql = sqlTurma($idEscritor, $semestre);
            // $result = mysql_query($sql);
            // $linhas = mysql_num_rows($result);

            // if ($linhas > 0) {
            //     for ($i=0; $i < $linhas; $i++) { 
            //         $_idturma = mysql_result($result, $i, "idturma");
            //         $_turma = mysql_result($result, $i, "turma");
            //         echo "<a id='aluno-' href='comunicacao.php?type=turma&turma={$_idturma}' style='text-decoration:none;'>{$_turma}</a>";
            //     }
            // } else {
            //     echo "Nenhuma turma encontrada.";
            // }


            // // mensagens de alunos para o professor
            // $result = mysql_query("SELECT idAluno, nome, DATE_FORMAT(data, '%m/%d/%Y às %Hh%i') AS data_formatada FROM mensagens 
            //     LEFT JOIN usuario ON usuario.idUsuario = mensagens.idAluno 
            //     WHERE tipoGrupo = 'professor' AND idGrupo = '$idEscritor' 
            //     GROUP BY idAluno");

            // echo "<div class='titulotopico' style='margin-top: 40px;'>
            //         <p>Mensagem de alunos</p>
            //     </div>";

            // $linhas = mysql_num_rows($result);

            // if ($linhas > 0) {
            //     for ($i=0; $i < $linhas; $i++) { 
            //         $_aluno = mysql_result($result, $i, "idAluno");
            //         $_nome = mysql_result($result, $i, "nome");
            //         $_data = mysql_result($result, $i, "data_formatada");
            //         echo "<a id='aluno-{$_aluno}' href='comunicacao.php?type=professor&aluno={$_aluno}' style='text-decoration:none;' title='{$_data}'>{$_nome}</a>";
            //     }
            // } else {
            //     echo "Nenhuma mensagem encontrada.";
            // }

        }
        
        ?>

    </div>
    <div class='conteudos'>
        <?php
        
        if (isset($tipo)) {

            if (isset($_GET["aluno"])) {
                echo "<input id='aluno' type='hidden' value='{$_GET["aluno"]}'>";
            }
            if (isset($_GET["turma"])) {
                echo "<input id='turma' type='hidden' value='{$_GET["turma"]}'>";
            }
            if (isset($_GET["curso"])) {
                if (in_array($_GET["curso"], $idsCursoProfessor))
                    echo "<input id='curso' type='hidden' value='{$_GET["curso"]}'>";
                else
                    exit("<script>location.href='comunicacao.php';</script>");
            }
 
            $t = $tipos[$tipo];
            echo "<div class='title' style='background-color: #FFF;'><h1 style='padding: 0; margin: 0;' id='titulo-chat'>{$t["nome"]}</h1> $selectProfessor</div>
                <div class='descricao'>
                    {$t["descricao"]} <b style='padding: 6px; cursor: pointer; background: #FFF; margin-left: 10px; border-radius: 15px;' onclick=\"$('.descricao').remove();\">Fechar</b>
                </div>

                    <ul class='conversa' id='conversa'>
                    </ul>";

            if ($tipo != "") {
                echo "<form class='escrever' onsubmit='salvarMensagem(event);'>
                    <input style='margin-right: 15px;' type='file' id='anexo' accept='.pdf,.png,.jpg,.doc,.docx'>
                    <textarea id='mensagem' rows='1' placeholder='Digite uma mensagem aqui...'></textarea>
                    <input type='hidden' id='tipo' value='$tipo'>
                    <input style='margin-left: 15px;' type='submit' value='Enviar' id='btn-enviar' disabled='disabled'>
                </form>";
            }
        }
        ?>
    </div>

    <script>
    $('#professor').change(function(e) {
        var tipo = $('#tipo').val();
        var professor = e.target.value;
        listarMensagens(tipo, professor, false, false, false);
    });

    $('#mensagem').keyup(function(e) {
        if (e.target.value.trim() != '') {
            $('#btn-enviar').removeAttr('disabled');
        } else {
            $('#btn-enviar').attr('disabled', 'disabled');
        }
    })

    $(document).ready(function() {
        var tipo = $('#tipo').val();
        var professor = null;
        var turma = null;
        var curso = null;
        var aluno = $('#aluno').val();

        if (tipo == 'professor') {
            professor = $('#professor').val();
            if (professor == null && aluno == null) {
                $('#professor').focus();
                return;
            } else if (aluno != null) {
                var nomeAluno = $('#aluno-' + aluno).text();
                $('#titulo-chat').text(nomeAluno);
            }
        } else if (tipo == 'turma') {
            turma = $('#turma').val();
        } else if (tipo == 'curso') {
            curso = $('#curso').val();
        }

        listarMensagens(tipo, professor, aluno, turma, curso);
    });

    function listarMensagens(tipo, professor, aluno, turma, curso) {
        $.ajax({
            type: 'get',
            url: './listarMensagens.php',
            data: {
                tipo,
                professor,
                aluno,
                turma,
                curso
            },
            dataType: 'json',
            success: function(data) {
                if (data) {
                    data.forEach(function(e) {
                        var anexo = '';

                        if (e.anexo != null) {
                            var ext = e.anexo.split('.').reverse()[0];

                            if (ext == 'png' || ext == 'jpg') {
                                anexo =
                                    `<div class='anexo anexo-foto'><a href='${e.anexo}' target='_blank'><img src='${e.anexo}'></a></div>`;
                            } else {
                                anexo =
                                    `<div class='anexo'><a href='${e.anexo}' target='_blank'>Ver anexo (.${ext.toUpperCase()})</a></div>`;
                            }
                        }

                        $('#conversa').append(
                            `<li class='mensagem-${e.tipo}' title='Enviado: ${e.data} (${e.tipo})'><span>${e.nome}: </span>${e.mensagem} ${anexo}</li>`
                        );
                    });

                    $('#conversa').scrollTop(999999999999999);
                }
            }
        });
    }

    function salvarMensagem(e) {
        e.preventDefault();

        $('#btn-enviar').val('Enviando...');

        var mensagem = $('#mensagem').val();
        var tipo = $('#tipo').val();
        var aluno = $('#aluno').val();
        var professor = null;
        var turma = null;

        if (tipo == 'professor') {
            professor = $('#professor').val();
            if (aluno == null && professor == null) {
                $('#professor').focus();
                return;
            }
        } else if (tipo == 'turma') {
            turma = $('#turma').val();
        } else if (tipo == 'curso') {
            curso = $('#curso').val();
        }

        if (mensagem.trim().length == 0) return;

        var form = new FormData();
        form.append('mensagem', mensagem);
        form.append('tipo', tipo);
        form.append('professor', professor);
        form.append('aluno', aluno);
        form.append('turma', turma);
        form.append('curso', curso);

        if ($('#anexo').get(0).files.length > 0) {
            form.append('anexo', $('#anexo').get(0).files[0]);
        }

        $.ajax({
            type: 'post',
            url: './salvarMensagens.php',
            data: form,
            // {
            //     mensagem,
            //     tipo,
            //     professor,
            //     aluno,
            //     turma,
            //     curso
            // },
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(res) {
                if (res) {
                    if (res.erro == false) {
                        // $('#mensagem').val('');
                        $('.escrever').get(0).reset();
                        $('#btn-enviar').attr('disabled', 'disabled');

                        var conversa = $('#conversa');
                        var anexo = '';

                        if (res.anexo != 'NULL') {
                            res.anexo = res.anexo.replace("'", '').replace("'", '');
                            var ext = res.anexo.split('.').reverse()[0];

                            if (ext == 'png' || ext == 'jpg') {
                                anexo =
                                    `<div class='anexo anexo-foto'><a href='${res.anexo}' target='_blank'><img src='${res.anexo}'></a></div>`;
                            } else {
                                anexo =
                                    `<div class='anexo'><a href='${res.anexo}' target='_blank'>Ver anexo (.${ext.toUpperCase()})</a></div>`;
                            }
                        }

                        conversa.append(`<li><span>EU: </span>${res.mensagem} ${anexo}</li>`)


                        conversa.scrollTop(999999999999999);
                    } else {
                        alert('Erro: ' + res.erro);
                    }

                    $('#btn-enviar').val('Enviar');
                }
            }
        });
    }
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