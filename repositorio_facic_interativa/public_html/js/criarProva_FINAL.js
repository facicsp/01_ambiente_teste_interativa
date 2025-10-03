const abecedario = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']

// ============================================================
// SISTEMA DE LOG AUTOMÁTICO
// ============================================================
const AutoLog = {
    logs: [],

    adicionar: function(tipo, mensagem, dados = null) {
        const timestamp = new Date().toISOString();
        const log = {
            timestamp: timestamp,
            tipo: tipo,
            mensagem: mensagem,
            dados: dados
        };

        this.logs.push(log);

        // Console para debug (opcional)
        console.log(`[${tipo.toUpperCase()}] ${mensagem}`, dados || '');

        // Enviar para servidor
        this.enviarParaServidor(log);
    },

    enviarParaServidor: function(log) {
        fetch('salvar_log_js.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(log)
        }).catch(err => {
            console.error('Erro ao enviar log:', err);
        });
    },

    info: function(mensagem, dados = null) {
        this.adicionar('info', mensagem, dados);
    },

    sucesso: function(mensagem, dados = null) {
        this.adicionar('sucesso', mensagem, dados);
    },

    erro: function(mensagem, dados = null) {
        this.adicionar('erro', mensagem, dados);
    },

    aviso: function(mensagem, dados = null) {
        this.adicionar('aviso', mensagem, dados);
    }
};

// Log inicial
AutoLog.info('Sistema de cadastro de prova iniciado');

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
            AutoLog.info('Tipo de questão alterado', {
                novoTipo: this.pergunta.tipo ? 'discursiva' : 'objetiva'
            });

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
            AutoLog.info('Alternativa adicionada', {
                total: this.pergunta.alternativas.length
            });
        },
        remove: function (i) {
            this.pergunta.alternativas.splice(i, 1)
            const correta = this.pergunta.correta
            this.pergunta.correta = correta == i ? false : correta
            AutoLog.info('Alternativa removida', {
                index: i,
                total: this.pergunta.alternativas.length
            });
        },
        correta: function (i) {
            this.pergunta.correta = i
            AutoLog.info('Alternativa correta selecionada', { index: i });
        },
        finalizar: function () {
            AutoLog.sucesso('Prova finalizada', { idProva: this.idProva });
            alert('Prova cadastrada com sucesso!')
            location.href = 'gravarProva_finalizar.php'
        },
        salvar: function () {
            AutoLog.info('='.repeat(60));
            AutoLog.info('INICIANDO SALVAMENTO DE QUESTÃO');
            AutoLog.info('='.repeat(60));

            this.pergunta.erro = false
            this.pergunta.enviado = true

            const alternativas = this.pergunta.alternativas.map((alt, i) => {
                if (alt.value.trim().length == 0 && !this.pergunta.tipo) this.pergunta.erro = true
                return alt.value
            })

            // VALIDAÇÃO
            AutoLog.info('Validação de campos', {
                tituloValido: this.titulo.trim().length > 0,
                descricaoValida: this.pergunta.descricao.value.trim().length > 0,
                corretaValida: this.pergunta.correta !== false || this.pergunta.tipo,
                erroAlternativas: this.pergunta.erro,
                tipo: this.pergunta.tipo ? 'discursiva' : 'objetiva'
            });

            if (
                !this.pergunta.erro && this.titulo.trim().length > 0 &&
                this.pergunta.descricao.value.trim().length > 0 &&
                (this.pergunta.correta !== false || this.pergunta.tipo)
            ) {

                AutoLog.sucesso('Validação passou - preparando dados');

                // Preparar FormData
                var form = new FormData();
                form.append('idProva', this.idProva);
                form.append('titulo', this.titulo);
                form.append('descricao', this.pergunta.descricao.value);
                form.append('correta', this.pergunta.correta);
                form.append('peso', this.pergunta.peso);
                form.append('tipo', this.pergunta.tipo ? 'dissertativa' : 'objetiva');

                alternativas.forEach((alt, i) => {
                    form.append(i, alt);
                });

                AutoLog.info('FormData preparado', {
                    idProva: this.idProva,
                    titulo: this.titulo,
                    descricao: this.pergunta.descricao.value.substring(0, 50) + '...',
                    correta: this.pergunta.correta,
                    peso: this.pergunta.peso,
                    tipo: this.pergunta.tipo ? 'dissertativa' : 'objetiva',
                    totalAlternativas: alternativas.length
                });

                AutoLog.info('Enviando requisição AJAX', {
                    url: 'gravarProva2_LIMPO.php',
                    method: 'POST',
                    contentType: 'multipart/form-data',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Enviar via AJAX
                axios({
                    method: "post",
                    url: "gravarProva2_LIMPO.php",
                    data: form,
                    headers: {
                        "Content-Type": "multipart/form-data",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                })
                .then(result => {
                    AutoLog.info('Resposta recebida do servidor', {
                        status: result.status,
                        statusText: result.statusText,
                        data: result.data,
                        headers: result.headers
                    });

                    // Verificar se é um número (formato antigo) ou objeto (formato novo)
                    let idProvaRetornado = null;

                    if (typeof result.data === 'object' && result.data.success) {
                        // Formato novo com JSON estruturado
                        AutoLog.sucesso('JSON estruturado recebido', result.data);
                        idProvaRetornado = result.data.idProva;
                    } else if (typeof result.data === 'number' || !isNaN(result.data)) {
                        // Formato antigo - só retorna o ID
                        AutoLog.sucesso('ID de prova recebido (formato numérico)', { id: result.data });
                        idProvaRetornado = result.data;
                    } else if (result.data) {
                        // Pode ser string com número
                        AutoLog.aviso('Resposta em formato inesperado', { data: result.data });
                        idProvaRetornado = result.data;
                    }

                    if (idProvaRetornado) {
                        AutoLog.sucesso('QUESTÃO SALVA COM SUCESSO!', {
                            idProva: idProvaRetornado,
                            numeroQuestao: this.numero
                        });

                        if (!this.idProva) this.idProva = idProvaRetornado
                        if (!this.pergunta.tipo && this.pergunta.correta !== false) {
                            document.getElementsByClassName('correta')[this.pergunta.correta].checked = false
                        }
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

                        AutoLog.info('Formulário resetado para próxima questão');

                    } else {
                        AutoLog.erro('Resposta vazia ou inválida do servidor', {
                            tipoResposta: typeof result.data,
                            resposta: result.data
                        });
                        alert('Erro: Resposta inesperada do servidor. Verifique o arquivo de log.')
                    }
                })
                .catch(err => {
                    AutoLog.erro('ERRO NA REQUISIÇÃO AJAX', {
                        mensagem: err.message,
                        response: err.response ? {
                            status: err.response.status,
                            statusText: err.response.statusText,
                            data: err.response.data,
                            headers: err.response.headers
                        } : 'Sem resposta do servidor',
                        request: err.request ? 'Requisição enviada mas sem resposta' : 'Erro ao montar requisição'
                    });

                    let mensagemErro = 'Erro ao salvar questão:\n\n';

                    if (err.response) {
                        mensagemErro += `Status: ${err.response.status}\n`;
                        mensagemErro += `Mensagem: ${err.response.statusText}\n`;
                        if (typeof err.response.data === 'string') {
                            mensagemErro += `Detalhes: ${err.response.data.substring(0, 200)}`;
                        }
                    } else if (err.request) {
                        mensagemErro += 'Servidor não respondeu.\nVerifique sua conexão.';
                    } else {
                        mensagemErro += err.message;
                    }

                    mensagemErro += '\n\nVerifique o arquivo de log para mais detalhes.';

                    alert(mensagemErro);
                })
            } else {
                AutoLog.aviso('Validação falhou - formulário não será enviado', {
                    motivosFalha: {
                        tituloVazio: this.titulo.trim().length == 0,
                        descricaoVazia: this.pergunta.descricao.value.trim().length == 0,
                        semCorreta: this.pergunta.correta === false && !this.pergunta.tipo,
                        erroAlternativas: this.pergunta.erro
                    }
                });
            }
        }
    }
})

AutoLog.info('Vue.js inicializado com sucesso');
