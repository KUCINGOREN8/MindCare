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

        "notification-blue": "#3B82F6",
        "notification-blue-bg" : "#EFF6FF",
        "notification-green": "#22C55E",
        "notification-green-bg" : "#F0FDF4",
        "notification-purple": "#A855F7",
        "notification-purple-bg" : "#FAF5FF",
        "notification-yellow" : "#EAB308",
        "notification-yellow-bg" : "#FEFCE8",
        
      },
    },
  },
  plugins: [
    require("tailwindcss"),
  ]
}
