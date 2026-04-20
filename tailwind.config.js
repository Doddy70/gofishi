/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'airbnb-red': '#FF385C',
        'airbnb-dark': '#222222',
        'airbnb-gray': '#717171',
        'airbnb-light-gray': '#F7F7F7',
        'airbnb-border': '#DDDDDD',
      },
      fontFamily: {
        sans: ['Circular', '-apple-system', 'BlinkMacSystemFont', 'Roboto', 'Helvetica Neue', 'sans-serif'],
      },
      boxShadow: {
        'airbnb': '0 6px 16px rgba(0,0,0,0.12)',
        'airbnb-hover': '0 6px 20px rgba(0,0,0,0.2)',
      },
      borderRadius: {
        'airbnb': '12px',
        'airbnb-pill': '32px',
      }
    },
  },
  plugins: [],
}
