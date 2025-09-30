<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title></title>


  <link rel="stylesheet" href="css/cadastro.css">
    <style>
    label {
        color: #069;
    }

    p {
        display: flex;
        align-items: center;
        text-align: left;
        color: #000;
        margin: 0 !important;
        padding: 7px 0 7px 10px !important;
    }

    input[type="radio"]:checked~* {
        background-color: #0080003b;
    }

    input[type="radio"] {
        margin-right: 10px;
        border: 0px;
        height: 1em;
        zoom: 1.3;
        -moz-transform: scale(1.3);
        -moz-transform-origin: 0 0;
        -o-transform: scale(1.3);
        -o-transform-origin: 0 0;
        -webkit-transform: scale(1.3);
        -webkit-transform-origin: 0 0;
        transform: scale(1.3);
        transform-origin: 0 0;
        float: left;
    }

    textarea {
        color: #000 !important;
    }

    .img img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .alternativa .img img {
        margin-top: -25px  !important;
    }

    .alternativa:hover {
        background-color: #DDD  !important;
    }

    .img {
        width: 100%  !important;
        height: 100px  !important;
    }

    .alternativa {
        border: 1px solid #DDD  !important;
        padding: 10px  !important;
    }

    .pergunta, .pergunta * {
        color: #333333 !important;
    }
    </style>
</head>

<body>
  <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "aluno") {
                $idAluno = $_SESSION["id"];
                $idDisciplina = $_SESSION["disciplina"];
                include "topo.php";
                
                ?>


  <div class="dados">
    <div class="barratitulo">
      <h1>Questionário</h1>
    </div>

    <?php
            include "LoginRestrito/conexao.php";

            $seguranca = new Seguranca();
            $idAplicar = $seguranca->antisql($_POST["id"]);

            $sql = "SELECT * FROM aplicarprova WHERE idDisciplina = '$idDisciplina' AND idAplicarProva = '$idAplicar'";
            $resultados = mysqli_query($conexao, $sql);
            $linhas = mysqli_num_rows($resultados);

            if ($linhas > 0) {
              $data = mysql_result($resultados, 0, 'fechamento');
              $idProva = mysql_result($resultados, 0, 'idProva');

              // $result = mysqli_query($conexao, "SELECT idAluno FROM respostas 
              //                       LEFT JOIN questao ON questao.idQuestao = respostas.idQuestao 
              //                       LEFT JOIN aplicarprova ON aplicarprova.idProva = questao.idProva 
              //                       WHERE respostas.idAluno = '$idAluno' AND aplicarprova.idAplicarProva = '$idAplicar'");


              // if (mysqli_num_rows($result) > 0 || strtotime(date("Y-m-d")) > strtotime($data)) {
              //   echo "<script>window.location = 'responder.php';</script>"; 
              //   exit;
              // }
              
              $result = mysqli_query($conexao, "SELECT * FROM questao2 WHERE idProva = '$idProva'");
              $linhas = mysqli_num_rows($result);
              
              echo "<form method='post' action='gravarResponderQuestionario2.php'>";
              echo "<input type='hidden' value='$idProva' name='txtIdProva'>";
              
              //echo "<script>console.log('SELECT * FROM aplicarprova WHERE idDisciplina = \"$idDisciplina\" AND idAplicarProva = \"$idAplicar\"')</script>";
              //echo "<script>console.log('SELECT * FROM questao2 WHERE idProva = \"$idProva\"')</script>";
              //echo "<script>console.log('idProva: $idProva')</script>";
              //echo "<script>console.log('Registros: $linhas')</script>";
              
              for ($i=0; $i<$linhas; $i++) {

                $idQuestao = mysql_result($result, $i, 'idQuestao');
                $descricao = htmlspecialchars(mysql_result($result, $i, 'descricao'));
                $tipo      = mysql_result($result, $i, 'tipo');
                
                preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $descricao, $matches);
                $image_url = "";
                if (sizeof($matches) > 0) {
                  $image_url = "<div class='img'><img src='{$matches[0]}'></div>"; 
                  $descricao = str_replace($matches[0], "", $descricao);
                }

                echo "<div class='pergunta'>
                        <br>
                        <p><b>".($i+1).")</b> &nbsp; $image_url $descricao</p>
                        <input required value='$idQuestao' type='hidden' name='txtIdQuestao[$i]'>";

                if ($tipo === "objetiva") {
                  $resultAlt = mysqli_query($conexao, "SELECT * FROM alternativa WHERE idQuestao = '$idQuestao'");

                  for ($iAlt=0; $iAlt < mysqli_num_rows($resultAlt); $iAlt++) { 
                    $idAlternativa = mysql_result($resultAlt, $iAlt, "idalternativa");
                    $alternativa   = htmlspecialchars(mysql_result($resultAlt, $iAlt, "alternativa"));
                    
                    preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $alternativa, $matches);
                    $image_url = "";
                    if (sizeof($matches) > 0) {
                      $image_url = "<div class='img'><img src='{$matches[0]}'></div>"; 
                      $alternativa = str_replace($matches[0], "", $alternativa);
                    }
                   
                    echo "<div class='alternativa'><input required value='$idAlternativa' type='radio' name='txtResposta[$i]'>$image_url <p>$alternativa</p></div>";
                  
                  }
                } else {
                  echo "<textarea required name='txtResposta[$i]' placeholder='Digite sua resposta aqui...'></textarea>";
                }

                echo "</div>";
              }

              echo "<input type='submit' value='Finalizar'></form><br><hr>";

            }
            ?>

  </div>
  </div>
  
  <!--script>
    (function() {
      var decodeEntities = (function() {
      // this prevents any overhead from creating the object each time
      var element = document.createElement('div');
      
      function decodeHTMLEntities (str) {
        if(str && typeof str === 'string') {
          // strip script/html tags
          str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
          str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
          element.innerHTML = str;
          str = element.textContent;
          element.textContent = '';
        }
        
        return str;
      }
      
      return decodeHTMLEntities;
    })();
    
    $('.pergunta').each(function(i,e) {
      $(" > p:eq(0)", this).html(decodeEntities($(" > p:eq(0)", this).html()));
    })
      })()
  </script-->
  
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