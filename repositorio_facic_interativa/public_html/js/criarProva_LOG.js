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
    peso: 1,
    tipo: false, // false -> objetiva // true -> dissertativa
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
        limparErro: function () {
            this.pergunta.erro = false
            this.pergunta.alternativas = [
                {
                    placeholder: 'Alternativa A',
                    value: ''
                },
                {
                    placeholder: 'Alternativa B',
                    value: ''
                }
            ]
            this.pergunta.correta = false
        },
        add: function () {
            const alternativa = abecedario[this.pergunta.alternativas.length] || 'Z'
            this.pergunta.alternativas.push({
                placeholder: `Alternativa ${alternativa}`,
                value: ''
            })
        },
        remove: function (i) {
            this.pergunta.alternativas.splice(i, 1)
            const correta = this.pergunta.correta
            this.pergunta.correta = correta == i ? false : correta
        },
        correta: function (i) {
            this.pergunta.correta = i
        },
        finalizar: function () {
            alert('Prova cadastrada com sucesso!')
            location.href = 'gravarProva_finalizar.php'
        },
        salvar: function () {
            console.log('========================================');
            console.log('INICIANDO SALVAMENTO - criarProva_LOG.js');
            console.log('========================================');

            this.pergunta.erro = false
            this.pergunta.enviado = true

            const alternativas = this.pergunta.alternativas.map((alt, i) => {
                if (alt.value.trim().length == 0 && !this.pergunta.tipo) this.pergunta.erro = true
                return alt.value
            })

            console.log('1. VALIDAÇÃO INICIAL');
            console.log('Título válido:', this.titulo.trim().length > 0);
            console.log('Descrição válida:', this.pergunta.descricao.value.trim().length > 0);
            console.log('Correta válida:', this.pergunta.correta !== false || this.pergunta.tipo);
            console.log('Erro em alternativas:', this.pergunta.erro);

            if (
                !this.pergunta.erro && this.titulo.trim().length > 0 &&
                this.pergunta.descricao.value.trim().length > 0 &&
                (this.pergunta.correta !== false || this.pergunta.tipo)
            ) {

                console.log('\n2. PREPARANDO FORMDATA');

                var form = new FormData();
                form.append('idProva', this.idProva);
                form.append('titulo', this.titulo);
                form.append('descricao', this.pergunta.descricao.value);
                form.append('correta', this.pergunta.correta);
                form.append('peso', this.pergunta.peso);
                form.append('tipo', this.pergunta.tipo ? 'dissertativa' : 'objetiva');

                console.log('FormData criado:');
                console.log('- idProva:', this.idProva);
                console.log('- titulo:', this.titulo);
                console.log('- descricao:', this.pergunta.descricao.value.substring(0, 50) + '...');
                console.log('- correta:', this.pergunta.correta);
                console.log('- peso:', this.pergunta.peso);
                console.log('- tipo:', this.pergunta.tipo ? 'dissertativa' : 'objetiva');

                alternativas.forEach((alt, i) => {
                    form.append(i, alt);
                    console.log('- alternativa ' + i + ':', alt.substring(0, 50) + '...');
                });

                if ($('#anexo').length > 0 && $('#anexo').get(0).files && $('#anexo').get(0).files.length > 0) {
                    const arquivo = $('#anexo').get(0).files[0];
                    form.append('anexo', arquivo);
                    console.log('- anexo:', arquivo.name, '(' + arquivo.size + ' bytes)');
                } else {
                    console.log('- anexo: nenhum arquivo selecionado');
                }

                console.log('\n3. ENVIANDO REQUISIÇÃO AXIOS');
                console.log('URL: gravarProva2_LOG.php');
                console.log('Método: POST');
                console.log('Content-Type: multipart/form-data');

                axios({
                    method: "post",
                    url: "gravarProva2_LOG.php",  // ← MUDADO PARA VERSÃO COM LOG
                    data: form,
                    headers: {
                        "Content-Type": "multipart/form-data",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                })
                .then(result => {
                    console.log('\n4. RESPOSTA RECEBIDA');
                    console.log('Status:', result.status);
                    console.log('Data:', result.data);

                    if (result.data && result.data.success) {
                        console.log('✅ SUCESSO!');
                        console.log('ID Prova:', result.data.idProva);
                        console.log('ID Questão:', result.data.idQuestao);
                        console.log('Arquivo de log:', result.data.log);

                        if (!this.idProva) this.idProva = result.data.idProva
                        if (!this.pergunta.tipo) document.getElementsByClassName('correta')[this.pergunta.correta].checked = false
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
                            peso: 1,
                            tipo: false,
                            correta: false,
                            erro: false,
                            enviado: false
                        }

                        $('#anexo').val('');

                        alert('✅ SUCESSO! Questão salva.\n\nVeja o log detalhado em:\n' + result.data.log);
                    } else if (result.data && result.data.error) {
                        console.error('❌ ERRO:', result.data.error);
                        if (result.data.log) {
                            console.error('Log disponível em:', result.data.log);
                        }
                        alert('❌ ERRO: ' + result.data.error + '\n\nVeja o log em: ' + (result.data.log || 'não disponível'));
                    } else {
                        console.error('❌ Resposta inesperada');
                        alert('Ops! Resposta inesperada do servidor.');
                    }
                })
                .catch(err => {
                    console.error('\n❌ ERRO NA REQUISIÇÃO');
                    console.error('Erro:', err);
                    console.error('Response:', err.response);

                    let mensagemErro = 'Ops! Houve algum erro.\n\n';

                    if (err.response) {
                        mensagemErro += 'Status: ' + err.response.status + '\n';
                        mensagemErro += 'Data: ' + JSON.stringify(err.response.data);
                    } else if (err.request) {
                        mensagemErro += 'Nenhuma resposta recebida do servidor.';
                    } else {
                        mensagemErro += err.message;
                    }

                    alert(mensagemErro);
                })
            } else {
                console.log('\n❌ VALIDAÇÃO FALHOU - não enviando requisição');
                console.log('Verifique os erros de validação acima');
            }
        }
    }
})
