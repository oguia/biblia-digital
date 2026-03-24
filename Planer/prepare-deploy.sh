#!/bin/bash
# Planer/prepare-deploy.sh

set -e

echo "🚀 Preparing Planer for deployment on Hostinger..."

cd "$(dirname "$0")"

echo "📦 Building React Client..."
cd client
npm install
npm run build
cd ..

echo "🧹 Cleaning up old artifacts..."
rm -rf deploy_artifact planer-deploy.zip

echo "📂 Creating deployment folder..."
mkdir deploy_artifact

echo "➡️ Copying backend API files..."
cp -r api deploy_artifact/

echo "➡️ Copying frontend files..."
cp -r client/dist/* deploy_artifact/

# Make sure uploads directory exists and is writable
mkdir -p deploy_artifact/api/uploads
chmod 777 deploy_artifact/api/uploads

# Create an .htaccess for the frontend to handle React routing (if using BrowserRouter, though we use HashRouter so this is optional but good practice)
cat << 'EOF' > deploy_artifact/.htaccess
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.html [L]
</IfModule>
EOF

echo "🤐 Zipping files..."
cd deploy_artifact
zip -r ../planer-deploy.zip ./* .htaccess
cd ..

echo "🧹 Cleaning up temporary files..."
rm -rf deploy_artifact

echo "✅ Deployment package created successfully: planer-deploy.zip"
echo "👉 You can now upload planer-deploy.zip to your Hostinger public_html or subfolder and extract it."
