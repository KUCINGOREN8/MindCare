import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./app/View/**/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/views/components/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/**/*.vue",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                Inter: ["Inter", "sans-serif"],
            },
        },
    },

    plugins: [],
};
