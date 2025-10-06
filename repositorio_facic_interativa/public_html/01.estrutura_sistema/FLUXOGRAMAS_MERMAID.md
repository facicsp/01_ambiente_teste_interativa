# FLUXOGRAMAS DO SISTEMA FACIC INTERATIVA
## Código Mermaid para Importação no Lucidchart

**Data:** 03/10/2025  
**Versão:** 1.0

---

## 📋 COMO IMPORTAR NO LUCIDCHART

### Passo a Passo:

1. **Abra o Lucidchart:** https://lucid.app/
2. **Crie novo documento** ou abra existente
3. **Importe Mermaid:**
   - Clique em **"Shapes"** (painel esquerdo)
   - Procure por **"Mermaid"** ou **"Import Data"**
   - Ou: Menu **"File"** → **"Import Data"** → **"Mermaid"**
4. **Cole o código** de cada fluxo abaixo
5. **Ajuste conforme necessário**

### Cores dos Fluxogramas:
- 🔵 **Azul (#006699)** - Administrador
- 🟢 **Verde (#28a745)** - Professor
- 🟠 **Laranja (#fd8609)** - Aluno
- ⚪ **Cinza (#6c757d)** - Sistema
- 🔴 **Vermelho (#dc3545)** - Erros

---

## FLUXO 1: MATRÍCULA DE ALUNO

```mermaid
flowchart TD
    A[Administrador Acessa Sistema] --> B[Cadastro de Alunos]
    B --> C[Preenche: Nome, Email, CPF, RA]
    C --> D{Validação}
    D -->|Erro| E[Exibe Erros]
    E --> C
    D -->|OK| F[Grava Aluno]
    F --> G[Aluno Cadastrado]
    G --> H[Acessa Cadastro Matrícula]
    H --> I[Seleciona Aluno e Turma]
    I --> J[Vincula à Turma]
    J --> K[Vincula às Disciplinas]
    K --> L[Gera Número Matrícula]
    L --> M[Matrícula Concluída]
    M --> N[Aluno Pode Fazer Login]
    
    style A fill:#006699,color:#fff
    style B fill:#006699,color:#fff
    style H fill:#006699,color:#fff
    style I fill:#006699,color:#fff
    style F fill:#6c757d,color:#fff
    style J fill:#6c757d,color:#fff
    style K fill:#6c757d,color:#fff
    style L fill:#6c757d,color:#fff
    style E fill:#dc3545,color:#fff
    style M fill:#28a745,color:#fff
    style N fill:#fd8609,color:#fff
```

---

## FLUXO 2: CRIAÇÃO DE AULA

```mermaid
flowchart TD
    A[Professor Acessa Sistema] --> B[Cadastro de Aulas]
    B --> C[Seleciona Disciplina]
    C --> D[Preenche Título e Descrição]
    D --> E{Upload Materiais?}
    E -->|Sim| F[Upload PDF, DOC, PPT]
    E -->|Não| G[Sem Materiais]
    F --> H{Valida}
    H -->|Erro| I[Formato Inválido]
    I --> F
    H -->|OK| G
    G --> J[Define Ordem]
    J --> K[Grava Aula]
    K --> L[Publica]
    L --> M[Alunos Visualizam]
    
    style A fill:#28a745,color:#fff
    style B fill:#28a745,color:#fff
    style C fill:#28a745,color:#fff
    style D fill:#28a745,color:#fff
    style J fill:#28a745,color:#fff
    style K fill:#6c757d,color:#fff
    style I fill:#dc3545,color:#fff
    style L fill:#28a745,color:#fff
    style M fill:#fd8609,color:#fff
```

---

## FLUXO 3: CRIAÇÃO E APLICAÇÃO DE PROVA

```mermaid
flowchart TD
    A[Professor Cria Prova] --> B[Define Título]
    B --> C{Adiciona Questões}
    C -->|Banco| D[Seleciona Existentes]
    C -->|Nova| E[Cria Nova Questão]
    D --> F[Define Peso]
    E --> G[Objetiva/Discursiva]
    G --> H[Cria Alternativas]
    H --> F
    F --> I[Configurações]
    I --> J[Ordem Aleatória]
    I --> K[Tentativas]
    I --> L[Tempo Limite]
    J --> M[Grava Prova]
    K --> M
    L --> M
    M --> N[Aplica para Turmas]
    N --> O[Define Prazo]
    O --> P[Publica]
    P --> Q[Aluno Visualiza]
    Q --> R[Aluno Responde]
    R --> S[Finaliza]
    S --> T[Sistema Registra]
    T --> U[Correção Automática Objetivas]
    U --> V[Professor Corrige Discursivas]
    V --> W[Calcula Nota Final]
    W --> X[Nota Disponível]
    
    style A fill:#28a745,color:#fff
    style N fill:#28a745,color:#fff
    style V fill:#28a745,color:#fff
    style Q fill:#fd8609,color:#fff
    style R fill:#fd8609,color:#fff
    style X fill:#fd8609,color:#fff
    style M fill:#6c757d,color:#fff
    style T fill:#6c757d,color:#fff
    style U fill:#6c757d,color:#fff
    style W fill:#6c757d,color:#fff
```

---

## FLUXO 4: ENVIO E CORREÇÃO DE ATIVIDADE

```mermaid
flowchart TD
    A[Professor Cria Atividade] --> B[Define Prazo e Pontos]
    B --> C[Publica]
    C --> D[Aluno Visualiza]
    D --> E{Prazo OK?}
    E -->|Não| F[Bloqueado]
    E -->|Sim| G[Upload Arquivo]
    G --> H{Valida}
    H -->|Erro| I[Formato Inválido]
    I --> G
    H -->|OK| J[Registra Envio]
    J --> K[Confirma para Aluno]
    K --> L[Professor Acessa Correções]
    L --> M[Baixa Arquivo]
    M --> N[Corrige]
    N --> O[Atribui Nota]
    O --> P[Escreve Feedback]
    P --> Q{Upload Correção?}
    Q -->|Sim| R[Anexa PDF Corrigido]
    Q -->|Não| S[Apenas Texto]
    R --> T[Grava Correção]
    S --> T
    T --> U[Aluno Visualiza Nota]
    
    style A fill:#28a745,color:#fff
    style L fill:#28a745,color:#fff
    style N fill:#28a745,color:#fff
    style O fill:#28a745,color:#fff
    style P fill:#28a745,color:#fff
    style D fill:#fd8609,color:#fff
    style G fill:#fd8609,color:#fff
    style U fill:#fd8609,color:#fff
    style J fill:#6c757d,color:#fff
    style T fill:#6c757d,color:#fff
    style F fill:#dc3545,color:#fff
    style I fill:#dc3545,color:#fff
```

---

## FLUXO 5: REGISTRO DE FREQUÊNCIA

```mermaid
flowchart TD
    A[Professor Acessa Turma] --> B[Seleciona Aula]
    B --> C[Registro de Frequência]
    C --> D{Opção}
    D -->|Todos P| E[Marca Todos Presentes]
    D -->|Todos F| F[Marca Todos Falta]
    D -->|Individual| G[Marca Por Aluno]
    E --> H[Grava]
    F --> H
    G --> H
    H --> I[Calcula Percentual]
    I --> J{< 75%?}
    J -->|Sim| K[Gera Alerta]
    J -->|Não| L[Status Normal]
    K --> M[Aluno Visualiza]
    L --> M
    M --> N{Justificar?}
    N -->|Sim| O[Upload Atestado]
    N -->|Não| P[Mantém Falta]
    O --> Q[Professor Analisa]
    Q --> R{Aceita?}
    R -->|Sim| S[Falta Justificada]
    R -->|Não| P
    
    style A fill:#28a745,color:#fff
    style B fill:#28a745,color:#fff
    style C fill:#28a745,color:#fff
    style Q fill:#28a745,color:#fff
    style M fill:#fd8609,color:#fff
    style O fill:#fd8609,color:#fff
    style H fill:#6c757d,color:#fff
    style I fill:#6c757d,color:#fff
    style S fill:#6c757d,color:#fff
    style K fill:#dc3545,color:#fff
```

---

## FLUXO 6: SISTEMA DE MENSAGENS

```mermaid
flowchart TD
    A[Aluno Acessa Dúvidas] --> B[Nova Mensagem]
    B --> C[Seleciona Disciplina]
    C --> D[Preenche Assunto]
    D --> E[Escreve Mensagem]
    E --> F{Anexo?}
    F -->|Sim| G[Upload Arquivo]
    F -->|Não| H[Envia]
    G --> H
    H --> I[Notifica Professor]
    I --> J[Professor Visualiza]
    J --> K[Lê Mensagem]
    K --> L{Baixa Anexo?}
    L -->|Sim| M[Download]
    L -->|Não| N[Responde]
    M --> N
    N --> O{Anexa Arquivo?}
    O -->|Sim| P[Upload Material]
    O -->|Não| Q[Envia Resposta]
    P --> Q
    Q --> R[Notifica Aluno]
    R --> S[Aluno Lê Resposta]
    
    style A fill:#fd8609,color:#fff
    style B fill:#fd8609,color:#fff
    style S fill:#fd8609,color:#fff
    style J fill:#28a745,color:#fff
    style K fill:#28a745,color:#fff
    style N fill:#28a745,color:#fff
    style H fill:#6c757d,color:#fff
    style I fill:#6c757d,color:#fff
    style Q fill:#6c757d,color:#fff
    style R fill:#6c757d,color:#fff
```

---

## FLUXO 7: PUBLICAÇÃO NO MURAL

```mermaid
flowchart TD
    A[Professor/Admin] --> B{Tipo?}
    B -->|Aviso| C[Secretaria]
    B -->|Mural| D[Disciplina]
    C --> E[Preenche Título]
    D --> E
    E --> F[Editor HTML]
    F --> G{Destinatário?}
    G -->|Todos| H[Geral]
    G -->|Específico| I[Seleciona Disciplina]
    H --> J{Anexo?}
    I --> J
    J -->|Sim| K[Upload]
    J -->|Não| L[Publica]
    K --> L
    L --> M[Alunos Visualizam]
    M --> N[Clica Detalhes]
    N --> O{Há Anexo?}
    O -->|Sim| P[Download]
    O -->|Não| Q[Leitura]
    
    style A fill:#006699,color:#fff
    style C fill:#006699,color:#fff
    style D fill:#28a745,color:#fff
    style M fill:#fd8609,color:#fff
    style N fill:#fd8609,color:#fff
    style L fill:#6c757d,color:#fff
```

---

## FLUXO 8: FÓRUM DE DISCUSSÃO

```mermaid
flowchart TD
    A[Professor Cria Tópico] --> B[Define Título]
    B --> C[Descrição]
    C --> D{Fixar?}
    D -->|Sim| E[Marca Fixado]
    D -->|Não| F[Tópico Normal]
    E --> G[Publica]
    F --> G
    G --> H[Aluno/Prof Acessa]
    H --> I[Lê Tópico]
    I --> J{Comentar?}
    J -->|Não| K[Apenas Lê]
    J -->|Sim| L[Escreve Comentário]
    L --> M{Anexo?}
    M -->|Sim| N[Upload]
    M -->|Não| O[Publica Comentário]
    N --> O
    O --> P[Notifica Participantes]
    P --> Q[Professor Modera]
    Q --> R{Ação?}
    R -->|Aprovar| S[Mantém]
    R -->|Remover| T[Exclui]
    R -->|Avaliar| U[Atribui Nota]
    
    style A fill:#28a745,color:#fff
    style Q fill:#28a745,color:#fff
    style H fill:#fd8609,color:#fff
    style L fill:#fd8609,color:#fff
    style G fill:#6c757d,color:#fff
    style O fill:#6c757d,color:#fff
    style P fill:#6c757d,color:#fff
    style T fill:#dc3545,color:#fff
```

---

## FLUXO 9: ACESSO BIBLIOTECA VIRTUAL

```mermaid
flowchart TD
    A[Aluno Acessa Bibliotecas] --> B{Escolhe}
    B -->|Pearson| C[Link Direto]
    B -->|Saraiva| D[Gera Token JWT]
    C --> E[Login Institucional]
    E --> F[Acesso Pearson]
    D --> G[Token ID+Nome+Email]
    G --> H[SSO Saraiva]
    H --> I{Token Válido?}
    I -->|Não| J[Erro]
    J --> D
    I -->|Sim| K[Acesso Direto]
    K --> L[Navega Acervo]
    F --> L
    L --> M[Seleciona Livro]
    M --> N[Leitura Online]
    N --> O{Retornar?}
    O -->|Sim| P[Volta FACIC]
    O -->|Não| M
    
    style A fill:#fd8609,color:#fff
    style M fill:#fd8609,color:#fff
    style N fill:#fd8609,color:#fff
    style D fill:#6c757d,color:#fff
    style G fill:#6c757d,color:#fff
    style H fill:#6c757d,color:#fff
    style J fill:#dc3545,color:#fff
    style K fill:#28a745,color:#fff
```

---

## FLUXO 10: UPLOAD DE VÍDEO

```mermaid
flowchart TD
    A[Professor Cadastra Vídeo] --> B{Tipo?}
    B -->|Local| C[Upload Arquivo]
    B -->|Externo| D[URL YouTube/Vimeo]
    C --> E{Valida Formato}
    E -->|Erro| F[Formato Inválido]
    F --> C
    E -->|OK| G{Tamanho}
    G -->|Grande| H[Arquivo Muito Grande]
    H --> C
    G -->|OK| I[Upload Servidor]
    I --> J[Gera Thumbnail]
    D --> K{URL Válida?}
    K -->|Não| L[Link Inválido]
    L --> D
    K -->|Sim| M[Extrai ID]
    J --> N[Preenche Dados]
    M --> N
    N --> O[Título]
    O --> P[Descrição]
    P --> Q[Disciplina]
    Q --> R{Vincular Aula?}
    R -->|Sim| S[Seleciona Aula]
    R -->|Não| T[Geral]
    S --> U[Publica]
    T --> U
    U --> V[Aluno Visualiza]
    V --> W[Assiste]
    W --> X[Registra Acesso]
    
    style A fill:#28a745,color:#fff
    style N fill:#28a745,color:#fff
    style V fill:#fd8609,color:#fff
    style W fill:#fd8609,color:#fff
    style I fill:#6c757d,color:#fff
    style U fill:#6c757d,color:#fff
    style X fill:#6c757d,color:#fff
    style F fill:#dc3545,color:#fff
    style H fill:#dc3545,color:#fff
    style L fill:#dc3545,color:#fff
```

---

## FLUXO 11: GAMIFICAÇÃO - PONTOS EXTRAS

```mermaid
flowchart TD
    A[Professor Cria Atividade Extra] --> B[Define Pontos Extras]
    B --> C[Prazo]
    C --> D[Critérios]
    D --> E[Publica]
    E --> F[Aluno Visualiza]
    F --> G{Deseja Participar?}
    G -->|Não| H[Não Faz]
    G -->|Sim| I[Realiza Atividade]
    I --> J[Envia]
    J --> K[Professor Corrige]
    K --> L[Atribui Pontos Extras]
    L --> M[Adiciona à Nota Final]
    M --> N[Verifica Conquistas]
    N --> O{Desbloqueou Medalha?}
    O -->|Sim| P[Notifica Medalha]
    O -->|Não| Q[Atualiza Ranking]
    P --> Q
    Q --> R[Aluno Vê Posição]
    
    style A fill:#28a745,color:#fff
    style K fill:#28a745,color:#fff
    style L fill:#28a745,color:#fff
    style F fill:#fd8609,color:#fff
    style I fill:#fd8609,color:#fff
    style R fill:#fd8609,color:#fff
    style M fill:#6c757d,color:#fff
    style N fill:#6c757d,color:#fff
    style P fill:#28a745,color:#fff
```

---

## FLUXO 12: GERAÇÃO DE BOLETIM

```mermaid
flowchart TD
    A[Sistema Calcula Notas] --> B[Por Disciplina]
    B --> C[Média Ponderada]
    C --> D{Média >= 7.0?}
    D -->|Sim| E[Aprovado]
    D -->|Não| F{Média >= 5.0?}
    F -->|Sim| G[Exame]
    F -->|Não| H[Reprovado]
    E --> I[Verifica Frequência]
    G --> I
    H --> I
    I --> J{Freq >= 75%?}
    J -->|Não| K[Reprovado por Falta]
    J -->|Sim| L[Situação OK]
    K --> M[Gera Boletim]
    L --> M
    M --> N[Professor Acessa]
    N --> O[Visualiza Boletim Turma]
    O --> P{Ajustes?}
    P -->|Sim| Q[Corrige Notas]
    Q --> A
    P -->|Não| R[Finaliza]
    R --> S[Aluno Acessa]
    S --> T[Visualiza Boletim]
    T --> U{Exportar?}
    U -->|Sim| V[Download PDF]
    U -->|Não| W[Apenas Visualiza]
    
    style A fill:#6c757d,color:#fff
    style C fill:#6c757d,color:#fff
    style I fill:#6c757d,color:#fff
    style M fill:#6c757d,color:#fff
    style N fill:#28a745,color:#fff
    style O fill:#28a745,color:#fff
    style Q fill:#28a745,color:#fff
    style S fill:#fd8609,color:#fff
    style T fill:#fd8609,color:#fff
    style E fill:#28a745,color:#fff
    style K fill:#dc3545,color:#fff
    style H fill:#dc3545,color:#fff
```

---

## 📊 RESUMO DOS FLUXOGRAMAS

| # | Fluxograma | Atores Principais | Arquivos Chave |
|---|------------|-------------------|----------------|
| 1 | Matrícula de Aluno | Admin | cadastroUsuario.php, cadastroMatricula.php |
| 2 | Criação de Aula | Professor | cadastroAula.php, gravarAula.php |
| 3 | Criação/Aplicação Prova | Professor, Aluno | cadastroProva2.php, responderQuestoes2.php |
| 4 | Envio/Correção Atividade | Professor, Aluno | cadastroAtividade.php, dadosAulas.php |
| 5 | Registro Frequência | Professor, Aluno | registrarFrequencia.php, exibirFrequencia.php |
| 6 | Sistema Mensagens | Aluno, Professor | mensagens.php, gravarMensagem.php |
| 7 | Publicação Mural | Professor/Admin, Aluno | cadastromural.php, visualizarmural.php |
| 8 | Fórum Discussão | Professor, Aluno | cadastrotopico.php, forum.php |
| 9 | Biblioteca Virtual | Aluno | bibliotecas.php, gerarTokenSaraiva.php |
| 10 | Upload Vídeo | Professor, Aluno | cadastrovideo.php, visualizarvideo.php |
| 11 | Gamificação | Professor, Aluno | cadastroExtra.php, verificarPontosExtras.php |
| 12 | Geração Boletim | Sistema, Professor, Aluno | gravarBoletim.php, exibirBoletim.php |

---

## ✅ CHECKLIST DE IMPORTAÇÃO

- [ ] Abrir Lucidchart
- [ ] Criar novo documento
- [ ] Importar Fluxo 1 (Matrícula)
- [ ] Importar Fluxo 2 (Aula)
- [ ] Importar Fluxo 3 (Prova)
- [ ] Importar Fluxo 4 (Atividade)
- [ ] Importar Fluxo 5 (Frequência)
- [ ] Importar Fluxo 6 (Mensagens)
- [ ] Importar Fluxo 7 (Mural)
- [ ] Importar Fluxo 8 (Fórum)
- [ ] Importar Fluxo 9 (Biblioteca)
- [ ] Importar Fluxo 10 (Vídeo)
- [ ] Importar Fluxo 11 (Gamificação)
- [ ] Importar Fluxo 12 (Boletim)
- [ ] Ajustar cores conforme perfis
- [ ] Adicionar legenda de cores
- [ ] Exportar como imagens
- [ ] Salvar no projeto

---

**Arquivo criado em:** 03/10/2025  
**Total de fluxogramas:** 12  
**Formato:** Mermaid (compatível com Lucidchart)  
**Status:** ✅ Pronto para importação

---

*Todos os fluxogramas estão prontos para serem importados no Lucidchart. Basta copiar e colar cada código Mermaid individualmente.*
