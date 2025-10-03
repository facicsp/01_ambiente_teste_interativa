<?php

session_start();
include './conexao.php';

$idAluno = $_SESSION["id"];

$result = mysql_query("SELECT idAluno FROM avaliacao_interna_aluno WHERE idAluno = '$idAluno'");
if (mysql_num_rows($result) > 0) exit("<script>location.href='index.php';</script>");

if (isset($_POST["acao"])) {
    // CREATE TABLE avaliacao_interna_aluno(
    //     idResposta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    //     idAluno VARCHAR(50),
    //     questao1 VARCHAR(50),
    //     questao2 VARCHAR(50),
    //     questao3 VARCHAR(50),
    //     questao4 VARCHAR(50),
    //     questao5 VARCHAR(50),
    //     questao6 VARCHAR(50),
    //     questao7 VARCHAR(50),
    //     questao8 VARCHAR(50),
    //     questao9 VARCHAR(50),
    //     questao10 VARCHAR(50),
    //     questao11 VARCHAR(50)
    // )
    $questao1 = $_POST["questao1"];
    $questao2 = $_POST["questao2"];
    $questao3 = $_POST["questao3"];
    $questao4 = $_POST["questao4"];
    $questao5 = $_POST["questao5"];
    $questao6 = $_POST["questao6"];
    $questao7 = $_POST["questao7"];
    $questao8 = $_POST["questao8"];
    $questao9 = $_POST["questao9"];
    $questao10 = $_POST["questao10"];
    $questao11 = $_POST["questao11"];

    mysql_query("INSERT INTO avaliacao_interna_aluno VALUES (NULL, '$idAluno', '$questao1', '$questao2', '$questao3', '$questao4', 
        '$questao5', '$questao6', '$questao7', '$questao8', '$questao9', '$questao10', '$questao11')");

    exit("<script>location.href='index.php';</script>");
}


?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <link rel="apple-touch-icon" type="image/png" href="https://static.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />
    <meta name="apple-mobile-web-app-title" content="CodePen">
    <link rel="shortcut icon" type="image/x-icon" href="https://static.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />
    <link rel="mask-icon" type="" href="https://static.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg" color="#111" />
    <title>Pesquisa</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;700&display=swap');

        body {
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 800px;
            width: 100%;
        }

        * {
            font-size: 1em;
            font-family: 'Roboto', sans-serif;
            box-sizing: border-box;
        }

        h1 {
            padding: 10px;
            font-size: 1.5em;
            text-align: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 5px 10px;
            border: 1px solid #DDD;
        }

        input[type="text"]:focus {
            border-color: #0075ff;
        }

        .questao {
            display: flex;
            flex-direction: column;
            padding: 20px 10px;
            border-radius: 5px;
            background-color: aliceblue;
            margin: 10px 0;
        }

        .alternativas {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .alternativas div {
            width: 50px;
        }

        .button {
            margin: 20px 10px;
            padding: 5px 10px;
            background-color: #0075ff;
            border: none;
            color: #FFF;
        }

        input,
        .button,
        label {
            transition: .3s;
            border-radius: 3px;
            border: none;
            cursor: pointer;
        }

        input:hover,
        .button:hover,
        label:hover {
            opacity: .8;
        }

        .alternativas-inline label {
            margin-right: 20px;
        }
    </style>
</head>

<body translate="no">
    <div class="container">
        <form action="#" method="post">
            <input type="hidden" name="acao" value="enviado">
            <h1>Sua opinião é de extrema importância para nós, nos ajude respondendo:</h1>
            <h2>Atenção: Para acessar a área do aluno é necessário responder este questionário.</h2><br>
            <div class="questao">
                <p>Qual é seu, RA, curso e semestre? (Não é obrigatório a identificação, mas é bem-vinda.)</p>
                <input type="text" name="questao1">
            </div>
            <div class="questao">
                <div>
                    <p>Qual nota, de 0 à 10, você consede ao Sistema de Atendimento ao Aluno da nossa instituição?
                    </p>
                </div>
                <div class="alternativas">
                    <script>
                        for (var i = 1; i <= 10; i++) {
                            document.write(`<div>
<label for="questao2-${i}">${i} <input type="radio" id="questao2-${i}" name="questao2" value="${i}" required></label>

          </div>`)
                        }
                    </script>
                </div>
            </div>
            <div class="questao">
                <div>
                    <p>Qual nota, de 0 à 10, você concede ao modelo emergencial
                        de Educação a Distância implantado para os cursos presenciais?
                    </p>
                </div>
                <div class="alternativas">
                    <script>
                        for (var i = 1; i <= 10; i++) {
                            document.write(`<div>
<label for="questao3-${i}">${i} <input type="radio" id="questao3-${i}" name="questao3" value="${i}" required></label>

          </div>`)
                        }
                    </script>
                </div>
            </div>
            <div class="questao">
                <div>
                    <p>Como você avalia o valor das mensalidades escolares e os
                        descontos concedidos pela FACIC, em uma escala de 0 à 10?
                    </p>
                </div>
                <div class="alternativas">
                    <script>
                        for (var i = 1; i <= 10; i++) {
                            document.write(`<div>
<label for="questao4-${i}">${i} <input type="radio" id="questao4-${i}" name="questao4" value="${i}" required></label>

          </div>`)
                        }
                    </script>
                </div>
            </div>
            <div class="questao">
                <div>
                    <p>Como você avalia as medidas tomadas pela FACIC
                        em face a pandemia do COVID-19, em escala de 0 à 10?
                    </p>
                </div>
                <div class="alternativas">
                    <script>
                        for (var i = 1; i <= 10; i++) {
                            document.write(`<div>
<label for="questao5-${i}">${i} <input type="radio" id="questao5-${i}" name="questao5" value="${i}" required></label>

          </div>`)
                        }
                    </script>
                </div>
            </div>
            <div class="questao">
                <div>
                    <p>Como vem sendo sua adaptação ao ensino a distância, em
                        uma escala de 0 à 10?
                    </p>
                </div>
                <div class="alternativas">
                    <script>
                        for (var i = 1; i <= 10; i++) {
                            document.write(`<div>
<label for="questao6-${i}">${i} <input type="radio" id="questao6-${i}" name="questao6" value="${i}" required></label>

          </div>`)
                        }
                    </script>
                </div>
            </div>
            <div class="questao">
                <div>
                    <p>Qual seu nível de aprendizagem, em uma escala de
                        0 à 10?
                    </p>
                </div>
                <div class="alternativas">
                    <script>
                        for (var i = 1; i <= 10; i++) {
                            document.write(`<div>
<label for="questao7-${i}">${i} <input type="radio" id="questao7-${i}" name="questao7" value="${i}" required></label>

          </div>`)
                        }
                    </script>
                </div>
            </div>
            <div class="questao">
                <p>Quais suas sugestões à Direção da FACIC</p>
                <textarea name="questao8" rows="4" required value=""></textarea>
            </div>
            <div class="questao">
                <div>
                    <p>Mesmo depois da pandemia do COVID-19, você pensa em solicitar a FACIC mais disciplinas em EAD nos cursos presenciais?
                    </p>
                </div>
                <div class="alternativas-inline">
                    <label for="questao9-sim">Sim <input type="radio" id="questao9-sim" name="questao9" value="sim" required></label>
                    <label for="questao9-nao">Não <input type="radio" id="questao9-nao" name="questao9" value="nao" required></label>
                </div>
            </div>
            <div class="questao">
                <div>
                    <p>Você trabalha atualmente?
                    </p>
                </div>
                <div class="alternativas-inline">
                    <label for="questao10-sim">Sim <input type="radio" id="questao10-sim" name="questao10" value="sim" required></label>
                    <label for="questao10-nao">Não <input type="radio" id="questao10-nao" name="questao10" value="nao" required></label>
                </div>
            </div>
            <div class="questao">
                <p>Qual a sua cidade de residência?</p>
                <input type="text" name="questao11" required>
            </div>
            <input type="submit" value="Enviar" class="button">
        </form>
    </div>
</body>

</html>