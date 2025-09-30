<?php include '../conexao.php'; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <title>FACIC INTERATIVA - BIBLIOTECAS</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <script>
  function trocaraba(id) {
    if (id == 1) {
      document.getElementById("periodicos").style.display = "block";
      document.getElementById("repositorio").style.display = "none";
    } else if (id == 2) {
      document.getElementById("periodicos").style.display = "none";
      document.getElementById("repositorio").style.display = "block";
    }
  }
  </script>
  <style>
  .img {
    width: 60%;
    margin-left: 20%;
    margin-right: 20%;
  }

  .p {
    font-weight: bold;
    text-align: center;
    margin-bottom: 44px;
  }

  .aba {
    width: 45%;
    background-color: #3cadd4;
    float: left;
    color: #FFF;
    text-align: center;
    padding: 10px 0;
    border-radius: 50px;
    margin-bottom: 10px;
  }

  .espaco {
    width: 10%;
    float: left;
    color: #FFF;
  }

  .titulo {
    width: 100%;
    background-color: #069;
    color: #FFF;
    font-size: 16px;
    float: left;
    padding: 10px 0;
    text-align: center;
    border-radius: 2px;
  }

  .periodico{
    padding: 10px;
    margin: 10px 0;
  }
  </style>
</head>

<body>
  <div class="bibliotecas">
    <br>
    <div>
      <p class="p" onclick="location.href='http://facicinterativa.com.br/biblioteca.html'">
        <img src="../imagens/pearson.png" class="img">
        Biblioteca Virtual
      </p>
    </div>
    <script type="text/javascript" src="http://cdn.editorasaraiva.com.br/bibliotecadigital/api/digital.library.min.js">
    </script>
    <div>
      <p class="p">
        <img src=" http://cdn.editorasaraiva.com.br/bibliotecadigital/img/btbd_h200.png" class="img" id="login"
          onClick="readerAccess('vale_282430', ':CODIGO_ALUNO', 'vale_gestao_20180418')">
        Biblioteca Digital - Gestão
      </p>
    </div>
    <div>
      <p class="p">
        <img src=" http://cdn.editorasaraiva.com.br/bibliotecadigital/img/btbd_h200.png" class="img" id="login"
          onClick="readerAccess('vale_282430', ':CODIGO_ALUNO', 'vale_direito_20180418')">
        Biblioteca Digital - Direito
      </p>
    </div>
  </div>
  <hr>
  <div class="aba" onclick="trocaraba(1)">Periódicos</div>
  <div class="espaco">.</div>
  <div class="aba" onclick="trocaraba(2)">Repositório</div>
  <div id="periodicos" tyle="sfloat:left;width:100%;">
    <?php
  
    $sql = "select p.*,a.area from periodico p,area a 
    WHERE p.idarea = a.idarea 
    ORDER BY a.area,p.titulo";
    $result = mysqli_query($conexao, $sql);
    $linhas = mysqli_num_rows($result);
    $areaauxiliar = "";
    if($linhas > 0){
    for($i=0;$i<$linhas;$i++){
    $titulo = mysql_result($result, $i, "titulo");
    $link = mysql_result($result, $i, "link");
    $area = mysql_result($result, $i, "area");
    if($area != $areaauxiliar){
    echo "<div class='titulo'>$area</div>";
    }
    echo "<p class='periodico'>"
    . "<a href='$link' target='_blank'>- $titulo</a>"
    . "</p>";

    $areaauxiliar = $area;
    }
    }else{
    echo "<p>Nenhum item encontrado.</p>";
    }
    echo "</div>";
    echo "<div id='repositorio' style='display:none;float:left;width:100%;'>";
    echo "<div class='titulo'>Repositórios</div>";
    //Exibir os repositórios
    $sql = "select * from formulario 
    ORDER BY titulo";
    $result = mysqli_query($conexao, $sql);
    $linhas = mysqli_num_rows($result);
    $areaauxiliar = "";
    if($linhas > 0){
    for($i=0;$i<$linhas;$i++){
    $titulo = mysql_result($result, $i, "titulo");
    $subtitulo = mysql_result($result, $i, "subtitulo");
    $autor = mysql_result($result, $i, "autor");
    $arquivo = mysql_result($result, $i, "arquivo");


    echo "<p class='periodico'>"
    . "<a href='repositorio/repositorio/$arquivo' target='_blank'>- $titulo - $subtitulo (Autor: $autor)</a>"
    . "</p>";

    $areaauxiliar = $area;
    }
    }else{
    echo "<p>Nenhum item encontrado.</p>";
    }
    echo "</div>";
  
  ?>
</body>

</html>