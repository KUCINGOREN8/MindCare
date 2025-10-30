/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#00C3B3',
        'primary-dark': '#2E6F6D',
        'secondary': '#FFDA4E',
        'background': '#FAFAFA',
        'caption': '#A1AAB2',
        'caption-dark': '#4D4D4E',
        'grey-border': '#ECECEC',
      },
    },
  },
  plugins: [],
}