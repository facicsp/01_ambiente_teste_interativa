<!DOCTYPE HTML>
<html>

<head>
  <title></title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/cadastro.css">
  <link rel="stylesheet" href="css/prova.css">
</head>

<body>
  <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
    if ($_SESSION["tipo"] == "administrador"  || $_SESSION["tipo"] == "professor") {
    include "topo.php";
    // include './Util.php';
    // $util = new Util();
    // include './conexao.php';
  ?>

  <div id="app">
    <div class="pergunta">
      <input type="text" v-model="titulo" v-bind:readonly="idProva"
        v-bind:title="idProva ? 'Esse título já foi definido e não pode ser alterado' : null"
        placeholder="Digite um título que seja intuitivo"><br>

      <div class="tipo">
        <span>Objetiva</span>
        <label class="switch">
          <input type="checkbox" v-model="pergunta.tipo" v-on:change="limparErro">
          <span class="slider round"></span>
        </label>
        <span>Discursiva</span>
      </div>
      
      <input v-model="pergunta.peso" type="number" step="0.1" min="0" max="10" placeholder="Peso">

      <h2 class="numero">{{numero}}</h2>
      <textarea v-bind:placeholder="pergunta.descricao.placeholder" v-model="pergunta.descricao.value" id="descricao"
        rows="6"></textarea><br>

      <div class="alternativa" v-if="!pergunta.tipo" v-for="(alternativa, i) in pergunta.alternativas"
        v-bind:class="{ selecionada: i===pergunta.correta }">
        <input type="radio" name="correta" v-on:click="correta(i)" class="correta">
        <textarea v-model="pergunta.alternativas[i].value" v-bind:placeholder="alternativa.placeholder"
          rows="4"></textarea>
        <button v-if="i > 1 && i == pergunta.alternativas.length - 1" v-on:click="remove(i)" title="Remover alternativa"
          class="remover">✘</button>
      </div>

      <div class="addAlternativa" title="Adicionar alternativa" v-if="!pergunta.tipo" v-on:click="add">Adicionar mais
        alternativas</div>

      <div style="margin: 10px 0;">
        <label for="anexo">Anexar arquivo (opcional):</label><br>
        <input type="file" id="anexo" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" style="margin-top: 5px;">
      </div>
      <p class="sucesso" v-if="sucesso">✔ Questão salva com sucesso!</p>
      <p class="erro" v-if="titulo.trim().length == 0 && pergunta.enviado">Ops! Preencha o campo título.</p>
      <p class="erro" v-if="pergunta.descricao.value.trim().length == 0 && pergunta.enviado">Ops! Preencha o campo
        descrição.</p>
      <p class="erro" v-if="pergunta.erro">Ops! Preencha todas as alternativas.</p>
      <p class="erro" v-if="(pergunta.correta === false && !pergunta.tipo) && pergunta.enviado">Ops! Selecione uma
        alternativa correta.</p>
      <button class="salvar botao sucesso" v-on:click="salvar">SALVAR QUESTÃO</button>
      <button class="salvar botao erro" v-on:click="finalizar" :disabled="!idProva"
        :title="!idProva">FINALIZAR</button>
    </div>
  </div>



  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.5.21/dist/vue.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
  <script src="js/criarProva.js"></script>
</body>

</html>
<?php
  } else {
    echo "Acesso negado! <a href='login.html'>Faça o login!</a>";
  }
} else {
  echo "<script>alert('É necessário fazer o login!'); window.location='login.html';</script>";
}
?>