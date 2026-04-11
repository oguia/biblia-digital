import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import { VitePWA } from 'vite-plugin-pwa'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    react(),
    VitePWA({
      registerType: 'autoUpdate',
      injectRegister: 'auto',
      includeAssets: ['favicon.svg', 'favicon.ico', 'robots.txt', 'logo.png', 'icon-faro.png'],
      manifest: {
        name: 'Faro de Ouro',
        short_name: 'Faro de Ouro',
        description: 'Gestão de Estoque para Pequenas e Médias Empresas',
        theme_color: '#000000',
        background_color: '#F4F4F4',
        display: 'standalone',
        orientation: 'portrait',
        icons: [
          {
            src: '/icon-faro.png',
            sizes: '192x192',
            type: 'image/png'
          },
          {
            src: '/icon-faro.png',
            sizes: '512x512',
            type: 'image/png'
          }
        ]
      }
    })
  ],
  base: './', // Important for Hostinger deployment
})
