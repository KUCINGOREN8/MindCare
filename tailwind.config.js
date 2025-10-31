export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  safelist: [
    'text-caption',
    'bg-caption',
    'text-captiondark',
    'bg-captiondark',
    'border-grey-border',
  ],
  theme: {
    extend: {
      colors: {
        primary: "#00C3B3",
        primarydark: "#2E6F6D",
        secondary: "#FFDA4E",
        background: "#FAFAFA",
        caption: "#A1AAB2",
        captiondark: "#4D4D4E",
        "grey-border": "#ECECEC",
      },
    },
  },
  plugins: [
    require("tailwindcss"),
  ]
}
