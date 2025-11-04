# Bíblia Digital Interativa - TODO

## Funcionalidades Principais

- [x] Interface de revista digital com páginas duplas (flip effect)
- [x] Navegação por gestos (arrastar para mudar de página)
- [x] Seleção de versão da Bíblia (NVI, RA, ACF, KJV, etc.)
- [x] Seleção de livro, capítulo e verso
- [ ] Busca de versículos por palavra-chave
- [x] Sistema de marcação de versículos (highlight com efeito marca-texto)
- [x] Persistência de marcações no localStorage
- [x] Visualização de versículos marcados
- [x] Remover marcações
- [x] Interface responsiva para mobile e desktop
- [x] Modo escuro/claro
- [x] Índice de livros da Bíblia
- [x] Navegação rápida entre capítulos

## Componentes

- [x] BibleViewer (componente principal de visualização - BibleReader.tsx)
- [x] PageFlip (efeito de virar página - navegação por gestos)
- [x] VersionSelector (seletor de versão)
- [x] BookSelector (seletor de livro)
- [x] ChapterSelector (seletor de capítulo)
- [ ] SearchBar (busca de versículos)
- [x] HighlightedVerse (verso com destaque)
- [x] Sidebar (navegação lateral - painel de marcações)

## Integração API

- [x] Integração com ABíbliaDigital API (fallback quando mock não disponível)
- [x] Mock data para demonstração (Gênesis NVI e RA)
- [x] Tratamento de erros de API
- [x] Loading states

## Testes e Refinamento

- [x] Testar navegação por gestos em mobile
- [x] Testar persistência de marcações
- [x] Testar responsividade
- [x] Testar mudança de versão
- [x] Testar tema escuro/claro
- [x] Testar seleção de múltiplos versículos
- [ ] Otimizar performance
- [ ] Verificar acessibilidade

## Funcionalidades Adicionais Implementadas

- [x] Tema escuro/claro com toggle
- [x] Dados mock para Gênesis (NVI e RA)
- [x] Interface de seleção intuitiva
- [x] Painel lateral com marcações salvas
- [x] Navegação por setas e seletor de capítulo

## Configuração Hostinger + Vercel

- [ ] Preparar arquivos estáticos para Hostinger (dist/public)
- [ ] Preparar código Node.js para Vercel
- [ ] Criar guia de deploy Hostinger (FTP)
- [ ] Criar guia de deploy Vercel (Git + MySQL)
- [ ] Configurar conexão MySQL Hostinger ↔ Vercel
- [ ] Criar arquivo vercel.json
- [ ] Testar integração completa

## Deploy Hostinger + Vercel

- [x] Arquitetura: Hostinger (estáticos) + Vercel (Node.js) + MySQL Hostinger
- [x] Preparar arquivos estáticos para Hostinger
- [x] Preparar código para Vercel
- [x] Guia passo a passo completo
- [x] Corrigir URL do Vercel no frontend
- [x] Recompilar e fazer upload dos arquivos atualizados
- [x] Testar integração completa
- [x] Marcar versículos funcionando
- [x] Corrigir erro 500 ao mudar de livro (causa: falta de dados mock)
- [ ] Gerar dados completos para todos os 66 livros
- [ ] Integrar com API ABíbliaDigital
- [ ] Testar todas as funcionalidades
- [ ] Fazer deploy final

## Expansão para Projeto Completo

- [ ] Todos os 66 livros da Bíblia com múltiplos capítulos
- [ ] 4 versões completas (NVI, RA, ACF, KJV)
- [ ] Busca por palavra-chave em todos os versículos
- [ ] Sistema de notas pessoais para marcações
- [ ] Compartilhamento de marcações (link/QR code)
- [ ] Índice de temas bíblicos
- [ ] Comentários para versículos
- [ ] Banco de dados para sincronização em nuvem
- [ ] Modo offline com localStorage
- [ ] Histórico de leitura
- [ ] Favoritos e coleções personalizadas
- [ ] Estatísticas de leitura
- [ ] Integração com redes sociais
- [ ] App mobile responsivo
- [ ] PWA (Progressive Web App) para instalação

