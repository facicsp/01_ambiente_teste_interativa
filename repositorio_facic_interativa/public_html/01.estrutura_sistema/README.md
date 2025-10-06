# DOCUMENTAÇÃO ESTRUTURAL DO SISTEMA FACIC INTERATIVA

> **📚 LEITURA OBRIGATÓRIA**  
> Todos os desenvolvedores, analistas e mantenedores devem ler esta documentação antes de realizar qualquer análise ou modificação no sistema.

---

## 📋 ÍNDICE

1. [Visão Geral do Sistema](#1-visão-geral-do-sistema)
2. [Estrutura de Perfis e Acessos](#2-estrutura-de-perfis-e-acessos)
3. [Módulos Principais](#3-módulos-principais)
4. [Fluxos Críticos](#4-fluxos-críticos)
5. [Arquitetura e Tecnologias](#5-arquitetura-e-tecnologias)
6. [Banco de Dados](#6-banco-de-dados)
7. [Segurança](#7-segurança)
8. [Guia Rápido de Desenvolvimento](#8-guia-rápido-de-desenvolvimento)
9. [Convenções e Padrões](#9-convenções-e-padrões)
10. [Documentação Complementar](#10-documentação-complementar)

---

## 1. VISÃO GERAL DO SISTEMA

### O que é o FACIC Interativa?

O **FACIC Interativa** é um **Learning Management System (LMS)** completo desenvolvido especificamente para a Faculdade FACIC. O sistema gerencia todo o ciclo educacional, desde o cadastro de alunos até a emissão de boletins finais.

### Características Principais

✅ **Gestão Acadêmica Completa**
- Cadastro de cursos, turmas, disciplinas e matrículas
- Controle de usuários (alunos, professores, administradores)

✅ **Gestão Pedagógica**
- Criação de aulas com materiais didáticos
- Upload de vídeos e conteúdos multimídia
- Organização de conteúdo por disciplina

✅ **Sistema de Avaliação**
- Banco de questões categorizado
- Criação de provas objetivas e discursivas
- Atividades com upload de arquivos
- Correção online com feedback

✅ **Controle Acadêmico**
- Lançamento de notas
- Registro de frequência
- Geração automática de boletins
- Cálculo de situação acadêmica

✅ **Comunicação Integrada**
- Sistema de mensagens entre alunos e professores
- Fórum de discussão por disciplina
- Mural de avisos institucional
- Notificações por email

✅ **Bibliotecas Virtuais**
- Integração com Pearson
- Integração com Minha Biblioteca (Saraiva)
- Acesso via SSO/JWT

✅ **Repositório Acadêmico**
- Sistema completo de catalogação
- Metadados em XML
- Classificação por área e instituição

✅ **Gamificação**
- Sistema de pontos extras
- Medalhas e conquistas
- Ranking de alunos
- Desafios acadêmicos

✅ **API Mobile**
- Endpoints REST completos
- Suporte para aplicativo mobile
- Autenticação JWT

### Estatísticas do Sistema

📊 **~300 arquivos PHP** principais  
📊 **~350+ funcionalidades** mapeadas  
📊 **~30 tabelas** no banco de dados  
📊 **45+ endpoints** de API  
📊 **3 perfis** de usuário distintos  
📊 **13 módulos** principais  

---

## 2. ESTRUTURA DE PERFIS E ACESSOS

### 2.1 ADMINISTRADOR

**Acesso:** Total ao sistema  
**Arquivo Index:** `index.php` (menu específico para admin)

#### Menu Principal (10 funcionalidades)

| Funcionalidade | Arquivo Principal | Descrição |
|----------------|-------------------|-----------|
| Cadastro de Alunos | `cadastroUsuario.php` | Gerenciamento completo de alunos |
| Cadastro de Professores | `cadastroProfessor.php` | Gerenciamento de professores |
| Cadastro de Cursos | `cadastroCurso.php` | Criação de cursos |
| Cadastro de Turmas | `cadastroTurma.php` | Gestão de turmas |
| Cadastro de Disciplinas | `cadastroDisciplina.php` | Gestão de disciplinas |
| Cadastro de Matrícula | `cadastroMatricula.php` | Matrícula de alunos |
| Cadastro de Aulas | `cadastroAula.php` | Criação de aulas |
| Aviso da Secretaria | `cadastromural.php` | Avisos institucionais |
| Repositório | `repositorio/cadastrorepositorio.php` | Repositório acadêmico |
| Periódicos | `cadastroperiodico.php` | Cadastro de periódicos |

### 2.2 PROFESSOR

**Acesso:** Intermediário - Gestão pedagógica  
**Arquivo Index:** `index.php` (menu específico para professor)

#### Menu Principal (16 funcionalidades)

| Funcionalidade | Arquivo Principal | Descrição |
|----------------|-------------------|-----------|
| Cadastro de Aulas | `cadastroAula.php` | Criar e gerenciar aulas |
| Cadastro de Professores | `cadastroProfessor.php` | Editar próprio cadastro |
| Dados Turmas | `dadosTurma.php` | Informações das turmas |
| Correção das Atividades | `dadosAulas.php` | Corrigir trabalhos enviados |
| Visualizar Notas e Frequência | `registros.php` | Consultar registros |
| Lançar Notas | `notas.php` | Lançamento de notas |
| Mensagens | `mensagens.php` | Sistema de comunicação |
| Cadastrar Vídeo | `cadastrovideo.php` | Upload de vídeos |
| Mural | `mural.php` | Publicar no mural |
| Visualizar Postagens | `visualizarmural.php` | Ver postagens |
| Sala Interativa | `dadosforum.php` | Fórum de discussão |
| Questionário | `aplicarProva2.php` | Criar e aplicar provas |
| Bibliotecas | `bibliotecas.php` | Acesso a bibliotecas |
| Pátio do Colégio | `comunicacao.php` | Comunicação geral |
| Sala de Professores | `comunicacao2.php` | Comunicação entre professores |

### 2.3 ALUNO

**Acesso:** Restrito - Visualização e interação  
**Arquivo Index:** `index.php` (menu específico para aluno)

#### Menu Principal (10 funcionalidades)

| Funcionalidade | Arquivo Principal | Descrição |
|----------------|-------------------|-----------|
| Cadastro de Alunos | `alterarUsuario.php` | Editar próprio cadastro |
| Meus Envios | `exibirNotas.php` | Atividades enviadas |
| Meu Boletim | `exibirBoletim.php` | Boletim completo |
| Minha Frequência | `exibirFrequencia.php` | Registro de presença |
| Situação Acadêmica | `situacao.php` | Status acadêmico |
| Dúvidas | `mensagens.php` | Mensagens com professores |
| Vídeos | `visualizarvideo.php` | Assistir vídeos |
| Materiais | `materiais.php` | Download de materiais |
| Bibliotecas | `bibliotecas.php` | Acesso a bibliotecas |
| Pátio do Colégio | `comunicacao.php` | Comunicação institucional |

---

Para documentação completa e detalhada, consulte:
- `MAPEAMENTO_COMPLETO.md` - Documento com 350+ funcionalidades mapeadas
- `GUIA_DESENVOLVIMENTO.md` - Guia completo para desenvolvedores

---

**Última Atualização:** 03/10/2025  
**Versão:** 1.0  
**Status:** ✅ Documentação Oficial