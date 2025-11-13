/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
      extend: {
        colors: {
          'nav-footer': '#a4e0f3',
          'button': '#5cb1e3',
          'text-dark': '#333333',
          'text-light': '#666666',
          'background-light': '#f9f9f9',
          'imprimir': '#8facd6',
        },
        fontFamily: {
          'poppins': ['Poppins', 'sans-serif'],
        },
      },
    },
    plugins: [],
  }
