# Instruções para Hospedagem na Hostinger

Este projeto foi construído para funcionar como um site estático, o que facilita muito a hospedagem em planos compartilhados da Hostinger.

## Passos para Instalação

### 1. Preparar os Arquivos
O processo de "build" já gerou a pasta `dist/` dentro da pasta `timeline/`. Esta pasta contém todos os arquivos otimizados para produção.

Se você baixou o código fonte, você precisa rodar o comando de build antes:
```bash
cd timeline
npm install
npm run build
```
Isso criará a pasta `dist`.

### 2. Upload para a Hostinger
1. Acesse o **Gerenciador de Arquivos** (File Manager) no painel da Hostinger.
2. Navegue até a pasta `public_html`.
3. Crie uma nova pasta chamada `timeline` (ou o nome que você preferir para o link, ex: `seusite.com/timeline`).
4. Abra essa pasta `timeline`.
5. Faça o upload de **todo o conteúdo** da pasta `dist/` para dentro dessa pasta na Hostinger.
   - Você deve ver um arquivo `index.html` e uma pasta `assets` dentro da sua pasta `timeline` no servidor.

### 3. Testar
Acesse `seu-dominio.com/timeline` no navegador. O site deve carregar com a linha do tempo e o mapa.

## Personalização

### Adicionar Imagens Reais
Para substituir as imagens de exemplo por imagens reais:
1. Coloque suas imagens (antigas e novas) em uma pasta pública ou serviço de hospedagem de imagens.
   - Uma opção fácil é criar uma pasta `imagens` dentro da pasta `timeline` na Hostinger e fazer upload das fotos lá.
   - O endereço da imagem seria `seu-dominio.com/timeline/imagens/foto-antiga.jpg`.
2. Edite o arquivo `timeline/src/data.ts` no código fonte.
3. Atualize os campos `imageOld` e `imageNew` com os URLs das suas imagens.
4. Rode `npm run build` novamente.
5. Faça o upload dos novos arquivos da pasta `dist` para a Hostinger (substituindo os antigos).

### Adicionar Mais Eventos
Edite o arquivo `timeline/src/data.ts` e adicione novos objetos ao array `timelineData`, seguindo o modelo existente. Lembre-se de rodar o build novamente após as alterações.
