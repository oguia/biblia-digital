/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'brand-dark': '#000000', // Black
        'brand-orange': '#bc9d64', // Gold Ochre
        'brand-white': '#FFFFFF',
        'brand-gray-light': '#F5F5F5',
      }
    },
  },
  plugins: [],
}
