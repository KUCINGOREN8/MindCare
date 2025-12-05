import { defineConfig } from "vite";

export default defineConfig({
    build: {
        manifest: true,
        outDir: "public/build",
        rollupOptions: {
            input: ["resources/css/app.css", "resources/js/app.js"],
        },
    },
});
