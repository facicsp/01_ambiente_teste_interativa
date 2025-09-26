<!--[if lt IE 9]>
          <script src="./assets/javascripts/html5.js"></script>
        <![endif]-->
<link rel="stylesheet" href="./assets/stylesheets/demo.css" />
<!--[if (gt IE 8) | (IEMobile)]><!-->
<link rel="stylesheet" href="./assets/stylesheets/unsemantic-grid-responsive.css" />
<!--<![endif]-->
<!--[if (lt IE 9) & (!IEMobile)]>
          <link rel="stylesheet" href="./assets/stylesheets/ie.css" />
        <![endif]-->

<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->

  <link rel="stylesheet" href="css/style.css" />

<script src="js/jquery.min.js"></script>
<script src="js/skel.min.js"></script>

<script src="js/init.js"></script>
<noscript>
  <link rel="stylesheet" href="css/skel.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/style-xlarge.css" />
</noscript>

<!--script>
  (function() {

      var idleDurationSecs = 7200;    // X number of seconds
      var redirectUrl = 'sair.php?redirect=true';  // Redirect idle users to this URL
      var idleTimeout; // variable to hold the timeout, do not modify

      var resetIdleTimeout = function() {

          // Clears the existing timeout
          if(idleTimeout) clearTimeout(idleTimeout);

          // Set a new idle timeout to load the redirectUrl after idleDurationSecs
          idleTimeout = setTimeout(function() { location.href = redirectUrl }, idleDurationSecs * 1000);
      };

      // Init on page load
      resetIdleTimeout();

      // Reset the idle timeout on any of the events listed below
      ['click', 'touchstart', 'mousemove'].forEach(function(evt) {
          document.addEventListener(evt, resetIdleTimeout, false)
      });

  })();
</script -->

<script>
function alteraSemestre(e) {
  location.href = 'trocarSemestre.php?semestre=' + e.value;
}

function coordenarProfessor(e) {
  location.href = 'coordenarProfessor.php?id=' + e.value;
}
</script>

<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<style>
.alerta {
  height: 50px;
  line-height: 50px;
  background-color: yellow;
}

.alerta a {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}

.selecionaSemestre {
  float: right;
  width: auto;
  margin: 3px 20px 3px 10px;
}

.selecionaSemestre p {
  height: 8px;
  margin: -17px 0 23px 0;
  padding: 0;
  color: #cccccc;
}

.selecionaSemestre select {
  height: 30px;
  width: auto;
  float: right;
}

.selecionaSemestre option {
  color: #000;
}
</style>

<?php

date_default_timezone_set('America/Sao_Paulo');

$hora =  date("H:i");
$hora = substr($hora, 0, 2);

$saudacao = "Olá ";

if($hora >= 12 && $hora <=18) 
  $saudacao = "Bom tarde ";
else if($hora > 4 && $hora < 12) 
  $saudacao = "Bom dia ";
else 
  $saudacao = "Bom noite ";


if(isset($_SESSION["nome"])) $nome = $_SESSION["nome"];
else $nome = $_SESSION["usuario"];

if ($_SESSION["tipo"] == "aluno")
  echo "<div class='alerta'><a href='ajuda.php'>>> VEJA AQUI COMO UTILIZAR O SISTEMA <<</a></div>";

echo "<div class='barratopo'>";

if ($_SESSION["tipo"] == "aluno") {
  echo "<a href='index.php'>Home</a> :: $saudacao $nome - <a href='sair.php'>Sair</a>";
} else {
  echo "<a href='index.php'>Home</a> :: $saudacao $nome - <a href='sair.php'>Sair</a>";

  if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
    echo '<div class="selecionaSemestre">
      <p>Selecionar semestre:</p>
      <select id="semestre" onChange="alteraSemestre(this)"> 
        <option value="2025/2" '. ($_SESSION["semestre"] == '2025/2' ? 'selected' : '') .'>2025/2</option>
        <option value="2025/1" '. ($_SESSION["semestre"] == '2025/1' ? 'selected' : '') .'>2025/1</option>
        <option value="2024/2" '. ($_SESSION["semestre"] == '2024/2' ? 'selected' : '') .'>2024/2</option>
        <option value="2024/1" '. ($_SESSION["semestre"] == '2024/1' ? 'selected' : '') .'>2024/1</option>
        <option value="2023/2" '. ($_SESSION["semestre"] == '2023/2' ? 'selected' : '') .'>2023/2</option>
        <option value="2023/1" '. ($_SESSION["semestre"] == '2023/1' ? 'selected' : '') .'>2023/1</option>
        <option value="2022/2" '. ($_SESSION["semestre"] == '2022/2' ? 'selected' : '') .'>2022/2</option>
        <option value="2022/1" '. ($_SESSION["semestre"] == '2022/1' ? 'selected' : '') .'>2022/1</option>
        <option value="2021/2" '. ($_SESSION["semestre"] == '2021/2' ? 'selected' : '') .'>2021/2</option>
        <option value="2021/1" '. ($_SESSION["semestre"] == '2021/1' ? 'selected' : '') .'>2021/1</option>
        <option value="2020/2" '. ($_SESSION["semestre"] == '2020/2' ? 'selected' : '') .'>2020/2</option>
        <option value="2020/1" '. ($_SESSION["semestre"] == '2020/1' ? 'selected' : '') .'>2020/1</option>
        <option value="2019/2" '. ($_SESSION["semestre"] == '2019/2' ? 'selected' : '') .'>2019/2</option>
        <option value="2019/1" '. ($_SESSION["semestre"] == '2019/1' ? 'selected' : '') .'>2019/1</option>
      </select>
    </div>';   
  } else if (isset($_SESSION["coordenados"]) && $_SESSION["coordenados"] != false) {
    echo '<div class="selecionaSemestre">
      <p>Coordenar professor:</p>
      <select id="professor" onChange="coordenarProfessor(this)">
      <option value="" disabled selected>Selecione um professor</option>';
      
      for ($i=0; $i < sizeof($_SESSION["coordenados"]); $i++) {
        echo '<option value="'.$i.'">'.$_SESSION["coordenados"][$i]["nome"].'</option>';
      }
      
    echo '</select>
    </div>'; 
  }
}

echo "</div>";

?>