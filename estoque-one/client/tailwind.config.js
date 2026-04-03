/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'brand-dark': '#333333', // Cinza Escuro
        'brand-orange': '#FF8A00', // Laranja Alerta
        'brand-white': '#FFFFFF',
        'brand-gray-light': '#F5F5F5',
      }
    },
  },
  plugins: [],
}