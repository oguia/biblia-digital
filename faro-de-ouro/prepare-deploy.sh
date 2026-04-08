#!/bin/bash
set -e

echo "Building React frontend..."
cd client
npm install --legacy-peer-deps
npm run build
cd ..

echo "Preparing Hostinger deployment..."
rm -rf hostinger_deploy
mkdir -p hostinger_deploy

# Move API to root
cp -r api hostinger_deploy/

# Move frontend assets to root
cp -r client/dist/* hostinger_deploy/

echo "Creating .htaccess for SPA routing at root..."
cat << 'HTACCESS' > hostinger_deploy/.htaccess
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /

  # API routing
  RewriteRule ^api/(.*)$ api/index.php?route=$1 [QSA,L]

  # Anti-scraping and copy protection
  RewriteCond %{HTTP_USER_AGENT} ^.*(HTTrack|Wget|curl|Python-urllib|libwww-perl|HttpClient|Java|Go-http-client|Scrapy|Bot|Spider).*$ [NC]
  RewriteRule .* - [F,L]

  # Redirect to SPA router if not a real file
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.html [L]

</IfModule>

# Disable directory listing
Options -Indexes
HTACCESS

echo "Zipping for deployment..."
rm -f faro-de-ouro-hostinger.zip
cd hostinger_deploy
zip -r ../faro-de-ouro-hostinger.zip .
cd ..
rm -rf hostinger_deploy

echo "Done! Upload 'faro-de-ouro-hostinger.zip' to Hostinger's public_html."
