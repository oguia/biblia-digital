#!/bin/bash

echo "Starting build process for ZapCRM..."

# Remove old zip
rm -f zapcrm.zip

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
npx esbuild index.js --bundle --platform=node --target=node18 --outfile=../api/bot/bot.cjs
cd ..

# 3. Create Deployment Directory
echo "Creating deployment package..."
mkdir -p zapcrm-deploy
mkdir -p zapcrm-deploy/bot
mkdir -p zapcrm-deploy/api
mkdir -p zapcrm-deploy/client

# Copy built frontend
cp -r client/dist/* zapcrm-deploy/

# Copy PHP API and SQLite DB structure
cp -r api/* zapcrm-deploy/api/
# Ensure the bot.cjs compiled file is moved to api/bot
# It's already generated there by esbuild

# Clean up any dev files if needed
rm -f zapcrm-deploy/api/db.sqlite

# Zip it up
echo "Zipping files..."
cd zapcrm-deploy
zip -r ../zapcrm.zip .
cd ..

# Cleanup
rm -rf zapcrm-deploy
echo "Build complete! Upload zapcrm.zip to Hostinger."
