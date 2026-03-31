#!/bin/bash
echo "Building VouZelar deployment package..."
cd client
npm install
npm run build
cd ..

rm -f vouzelar-hostinger.zip
zip -r vouzelar-hostinger.zip client/dist/ api/
echo "Done! The file vouzelar-hostinger.zip is ready for deployment."
