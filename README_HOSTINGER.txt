INSTRUÇÕES FINAIS PARA HOSTINGER

Se você não encontrar o arquivo `HOSTINGER_DEPLOY_V2.zip`, siga estes passos simples:

1. A pasta `ogm` que você vê aqui contém EXATAMENTE o mesmo conteúdo que estaria no ZIP.
2. Entre na pasta `ogm`.
3. Selecione TODOS os arquivos dentro dela (app, config, public, views, .htaccess, etc).
4. Crie um arquivo ZIP com esses arquivos.
5. Suba esse ZIP para o `public_html` da Hostinger.

Dessa forma, você terá o site funcionando.

Para os dados reais:
O arquivo `import_data.sql` dentro da pasta `ogm` é o que contém os nomes reais (Madalosso, Barolo, etc).
Importe-o no phpMyAdmin.

Para testar:
Acesse https://seudominio.com/root_check.php após subir os arquivos.
