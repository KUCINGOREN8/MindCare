import { defineConfig } from "vite";

export default defineConfig({
    publicDir: false,  
    build: {
        manifest: true,
        outDir: "public/dist",
        emptyOutDir: true,
        rollupOptions: {
            input: ["resources/css/app.css", "resources/js/app.js"],
        },
    },
});
