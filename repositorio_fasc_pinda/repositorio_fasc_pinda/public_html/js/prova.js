const abecedario = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']

const pergunta = {
  descricao: {
    placeholder: 'Descrição...',
    value: ''
  },
  alternativas: [
    {
      placeholder: 'Alternativa A',
      value: ''
    },
    {
      placeholder: 'Alternativa B',
      value: ''
    }
  ],
  correta: false,
  erro: false,
  enviado: false
}

new Vue({
  el: '#app',
  data: {
    titulo: '',
    idProva: false,
    sucesso: false,
    numero: 1,
    pergunta
  },
  methods: {
    add: function() {
      const alternativa = abecedario[this.pergunta.alternativas.length] || 'Z'
      this.pergunta.alternativas.push({
        placeholder: `Alternativa ${alternativa}`,
        value: ''
      })
    },
    remove: function(i) {
      this.pergunta.alternativas.splice(i, 1)
      const correta = this.pergunta.correta
      this.pergunta.correta = correta == i ? false : correta
    },
    correta: function(i) {
      this.pergunta.correta = i
    },
    finalizar: function() {
      alert('Prova cadastrada com sucesso!')
      location.href = 'gravarProva_finalizar.php'
    },
    salvar: function() {
      this.pergunta.erro = false
      this.pergunta.enviado = true

      const alternativas = this.pergunta.alternativas.map((alt, i) => {
        if (alt.value.trim().length == 0) this.pergunta.erro = true

        return alt.value
      })

      if (
        !this.pergunta.erro &&
        this.pergunta.descricao.value.trim().length > 0 &&
        this.pergunta.correta !== false &&
        this.titulo.trim().length > 0
      ) {
        const body = {
          idProva: this.idProva,
          titulo: this.titulo,
          descricao: this.pergunta.descricao.value,
          correta: this.pergunta.correta,
          ...alternativas
        }

        axios
          .post('gravarProva.php', { ...body })
          .then(result => {
            if (result.data) {
              if (!this.idProva) this.idProva = result.data
              document.getElementsByClassName('correta')[this.pergunta.correta].checked = false
              document.getElementById('descricao').focus()
              this.sucesso = true
              setTimeout(() => (this.sucesso = false), 3000)

              this.numero++
              this.pergunta = {
                descricao: {
                  placeholder: 'Descrição...',
                  value: ''
                },
                alternativas: [
                  {
                    placeholder: 'Alternativa A',
                    value: ''
                  },
                  {
                    placeholder: 'Alternativa B',
                    value: ''
                  }
                ],
                correta: false,
                erro: false,
                enviado: false
              }
            } else {
              alert('Ops! Houve algum erro.')
            }
          })
          .catch(err => {
            alert('Ops! Houve algum erro.' + err)
          })
      }
    }
  }
})

//window.onbeforeunload = function() {
//  if (parseInt($('.numero').text()) < 11) {
//    return 'Se você sair sem completar 10 questões a prova será perdida.'
//  }
//}
