#!/bin/bash

echo "Starting build process for ZapCRM..."

# Remove old zips
rm -f zapcrm-panel.zip
rm -f zapcrm-bot.zip
rm -f zapcrm-final.zip

# 1. Build the React Frontend
echo "Building React Client..."
cd client
npm install
npm run build
cd ..

# 2. Compile the Node.js Bot with esbuild
echo "Compiling Node.js WhatsApp Bot with esbuild..."
cd bot
npm install
npx esbuild index.js --bundle --platform=node --target=node18 --outfile=bot.cjs --external:pino --external:qrcode --external:express --external:cors --external:axios --external:http --external:dotenv
cd ..

# 3. Create Deployment Directory for PHP/React CRM (The Panel)
echo "Creating deployment package for PHP/React Panel..."
mkdir -p zapcrm-deploy-panel
mkdir -p zapcrm-deploy-panel/api

# Copy built frontend
cp -r client/dist/* zapcrm-deploy-panel/

# Copy PHP API and SQLite DB structure
cp -r api/* zapcrm-deploy-panel/api/

# Clean up any dev files if needed
rm -f zapcrm-deploy-panel/api/db.sqlite
rm -f zapcrm-deploy-panel/api/qr.png
rm -f zapcrm-deploy-panel/api/bot_auth.txt
rm -f zapcrm-deploy-panel/api/bot_port.txt

# Zip Panel
echo "Zipping Panel files..."
cd zapcrm-deploy-panel
zip -r ../zapcrm-panel.zip .
cd ..

# 4. Create Deployment Directory for Node.js App (The Bot)
echo "Creating deployment package for Node.js App..."
mkdir -p zapcrm-deploy-bot

# Copy built bot file and package.json (so Hostinger can read node versions)
cp bot/bot.cjs zapcrm-deploy-bot/
cp bot/package.json zapcrm-deploy-bot/
echo "PORT=3000\nWEBHOOK_URL=\nBOT_TOKEN=" > zapcrm-deploy-bot/.env-example

# Zip Bot
echo "Zipping Bot files..."
cd zapcrm-deploy-bot
zip -r ../zapcrm-bot.zip .
cd ..

# Cleanup
rm -rf zapcrm-deploy-panel
rm -rf zapcrm-deploy-bot

echo "Build complete! Two files generated:"
echo "1. zapcrm-panel.zip -> Upload to your main domain (PHP Site)"
echo "2. zapcrm-bot.zip -> Upload to your subdomain (Node.js App)"
