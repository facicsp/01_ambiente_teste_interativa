# MAPEAMENTO COMPLETO DE COMUNICAÇÕES POR E-MAIL
## Sistema FACIC Interativa - Fluxos de Notificação

**Data:** 05/10/2025  
**Versão:** 1.0  
**Complemento ao:** FLUXOGRAMAS_MERMAID.md

---

## 📧 ÍNDICE DE COMUNICAÇÕES

### 1. MÓDULO ADMINISTRATIVO
- 1.1 Cadastro e Matrícula
- 1.2 Gestão de Turmas
- 1.3 Gestão de Professores

### 2. MÓDULO ACADÊMICO
- 2.1 Aulas e Conteúdos
- 2.2 Avaliações e Notas
- 2.3 Atividades e Trabalhos
- 2.4 Frequência

### 3. MÓDULO DE COMUNICAÇÃO
- 3.1 Mensagens Diretas
- 3.2 Mural e Avisos
- 3.3 Fórum e Discussões

### 4. MÓDULO DE BIBLIOTECA
- 4.1 Materiais Didáticos
- 4.2 Vídeos e Multimídia

### 5. NOTIFICAÇÕES DO SISTEMA
- 5.1 Alertas Acadêmicos
- 5.2 Prazos e Lembretes
- 5.3 Confirmações

---

## 1. MÓDULO ADMINISTRATIVO

### 1.1 CADASTRO E MATRÍCULA

#### E-MAIL 1.1.1: Boas-vindas ao Aluno
```
GATILHO: gravarUsuario.php (tipo=aluno)
DESTINATÁRIO: Aluno (novo cadastro)
REMETENTE: noreply@facic.edu.br / secretaria@facic.edu.br

ASSUNTO: 🎓 Bem-vindo(a) à FACIC Interativa!

CONTEÚDO:
---
Olá [NOME_ALUNO],

Seja bem-vindo(a) ao sistema FACIC Interativa!

📋 Seus dados de acesso:
• RA: [RA_ALUNO]
• Senha: [SENHA_INICIAL]
• Link: https://interativa.facic.edu.br

⚠️ IMPORTANTE: Altere sua senha no primeiro acesso!

📚 Próximos passos:
1. Faça login no sistema
2. Complete seu perfil
3. Confira suas disciplinas
4. Explore os materiais disponíveis

Dúvidas? Entre em contato:
📧 suporte@facic.edu.br
📱 (16) 3377-1420

Bons estudos!
Equipe FACIC
---
```

#### E-MAIL 1.1.2: Confirmação de Matrícula
```
GATILHO: gravarMatricula.php (após vinculação)
DESTINATÁRIO: Aluno
REMETENTE: secretaria@facic.edu.br

ASSUNTO: ✅ Matrícula Confirmada - [TURMA] / [SEMESTRE]

CONTEÚDO:
---
Olá [NOME_ALUNO],

Sua matrícula foi confirmada com sucesso!

📋 Dados da Matrícula:
• Nº Matrícula: [NUM_MATRICULA]
• Turma: [NOME_TURMA]
• Período: [SEMESTRE]
• Data: [DATA_MATRICULA]

📚 Disciplinas Matriculadas:
[LISTA_DISCIPLINAS]
• [DISCIPLINA_1] - Prof. [PROFESSOR_1]
• [DISCIPLINA_2] - Prof. [PROFESSOR_2]
...

📅 Calendário Acadêmico:
• Início das aulas: [DATA_INICIO]
• Término: [DATA_FIM]
• Férias: [PERIODO_FERIAS]

🔔 O que fazer agora:
1. Acesse o sistema em https://interativa.facic.edu.br
2. Confira o material de cada disciplina
3. Verifique o cronograma de aulas
4. Baixe o calendário acadêmico

Bom semestre letivo!
Secretaria Acadêmica FACIC
---
```

#### E-MAIL 1.1.3: Dados de Acesso - Professor
```
GATILHO: gravarUsuario.php (tipo=professor)
DESTINATÁRIO: Professor (novo cadastro)
REMETENTE: coordenacao@facic.edu.br

ASSUNTO: 👨‍🏫 Credenciais de Acesso - FACIC Interativa

CONTEÚDO:
---
Prezado(a) Prof. [NOME_PROFESSOR],

Suas credenciais de acesso ao sistema foram criadas:

🔑 Dados de Acesso:
• Usuário: [RA_PROFESSOR]
• Senha: [SENHA_INICIAL]
• Portal: https://interativa.facic.edu.br

📋 Disciplinas Atribuídas:
[LISTA_DISCIPLINAS_PROF]

📚 Recursos Disponíveis:
• Cadastro de aulas e materiais
• Aplicação de provas e atividades
• Registro de notas e frequência
• Sistema de mensagens
• Mural e fórum
• Biblioteca virtual

📖 Documentação:
• Manual do Professor: [LINK_MANUAL]
• Tutoriais em vídeo: [LINK_VIDEOS]
• Suporte técnico: suporte@facic.edu.br

Conte conosco!
Coordenação Acadêmica
---
```

### 1.2 GESTÃO DE TURMAS

#### E-MAIL 1.2.1: Criação de Turma - Notificação Professor
```
GATILHO: gravarTurma.php + vinculação professor
DESTINATÁRIO: Professor
REMETENTE: coordenacao@facic.edu.br

ASSUNTO: 📚 Nova Turma Atribuída - [DISCIPLINA]

CONTEÚDO:
---
Prof. [NOME_PROFESSOR],

Uma nova turma foi atribuída a você:

📋 Informações da Turma:
• Disciplina: [NOME_DISCIPLINA]
• Turma: [CODIGO_TURMA]
• Período: [SEMESTRE]
• Horário: [DIAS_HORARIOS]
• Sala: [SALA] ou Online

👥 Alunos Matriculados: [TOTAL_ALUNOS]

📅 Calendário:
• Início: [DATA_INICIO]
• Término: [DATA_FIM]
• Carga Horária: [CH] horas

🎯 Próximos Passos:
1. Acesse o sistema
2. Configure o plano de ensino
3. Cadastre as primeiras aulas
4. Publique material introdutório

Acesse: https://interativa.facic.edu.br

Sucesso!
Coordenação
---
```

#### E-MAIL 1.2.2: Aluno Adicionado à Turma
```
GATILHO: Vinculação aluno-turma (SQL INSERT turma_matricula)
DESTINATÁRIO: Aluno
CC: Professor (opcional)
REMETENTE: secretaria@facic.edu.br

ASSUNTO: 📚 Você foi matriculado em [DISCIPLINA]

CONTEÚDO:
---
Olá [NOME_ALUNO],

Você foi matriculado na disciplina:

📖 [NOME_DISCIPLINA]
👨‍🏫 Professor: [NOME_PROFESSOR]
📅 Período: [SEMESTRE]
🕐 Horário: [DIAS_HORARIOS]

📋 Informações Importantes:
• Turma: [CODIGO_TURMA]
• Carga Horária: [CH]h
• Modalidade: [PRESENCIAL/EAD/HÍBRIDA]

📚 Já disponível no sistema:
• Plano de ensino
• Cronograma de aulas
• Material da primeira aula
• Fórum da disciplina

🔗 Acesse agora:
https://interativa.facic.edu.br

Bons estudos!
Secretaria FACIC
---
```

---

## 2. MÓDULO ACADÊMICO

### 2.1 AULAS E CONTEÚDOS

#### E-MAIL 2.1.1: Nova Aula Publicada
```
GATILHO: gravarAula.php (status=publicado)
DESTINATÁRIO: Todos os alunos da turma
REMETENTE: [EMAIL_PROFESSOR] / noreply@facic.edu.br

ASSUNTO: 📖 Nova Aula: [TÍTULO_AULA] - [DISCIPLINA]

CONTEÚDO:
---
Olá, turma [CODIGO_TURMA]!

O Prof. [NOME_PROFESSOR] publicou uma nova aula:

📚 [TÍTULO_AULA]
📅 Data: [DATA_PUBLICACAO]
⏱️ Duração estimada: [DURACAO] min

📋 Conteúdo:
[DESCRIÇÃO_AULA]

📎 Materiais Disponíveis:
• [MATERIAL_1.pdf]
• [MATERIAL_2.pptx]
• [VIDEO_AULA]

🎯 Objetivos de Aprendizagem:
[OBJETIVOS]

🔗 Acesse a aula:
https://interativa.facic.edu.br/visualizaraula.php?id=[ID_AULA]

Bons estudos!
---
```

#### E-MAIL 2.1.2: Material Didático Atualizado
```
GATILHO: alterarAula.php (novo anexo ou edição)
DESTINATÁRIO: Alunos da turma
REMETENTE: [EMAIL_PROFESSOR]

ASSUNTO: 🔄 Material Atualizado - [TÍTULO_AULA]

CONTEÚDO:
---
Atenção, turma!

A aula "[TÍTULO_AULA]" foi atualizada:

🆕 O que mudou:
• [DESCRICAO_MUDANCA]

📎 Novos Materiais:
• [ARQUIVO_NOVO_1]
• [ARQUIVO_NOVO_2]

⚠️ Ação Necessária:
[ORIENTACAO_PROFESSOR]

🔗 Acesse: