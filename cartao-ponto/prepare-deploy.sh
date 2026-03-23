#!/bin/bash
echo "Compiling frontend..."
cd client
npm run build
cd ..

echo "Packaging backend and compiled frontend into cartao-ponto.zip..."
rm -rf deploy_tmp cartao-ponto.zip
mkdir deploy_tmp

cp -r api deploy_tmp/
cp -r client/dist/* deploy_tmp/
cp client/dist/.htaccess deploy_tmp/ 2>/dev/null || true

cd deploy_tmp
zip -r ../cartao-ponto.zip .
cd ..

rm -rf deploy_tmp
echo "Build and packaging complete! cartao-ponto.zip is ready for Hostinger deployment."
