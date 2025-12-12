import { defineConfig } from "vite";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import { VueRouterAutoImports } from "unplugin-vue-router";
import vue from "@vitejs/plugin-vue";
import tailwindcss from "@tailwindcss/vite";
import path from "path";

// https://vite.dev/config/
export default defineConfig({
    plugins: [
        vue(),

        Components({
            dirs: ["src/components", "src/layouts"],
            dts: true,
        }),

        AutoImport({
            // Auto import composables
            imports: ["vue", VueRouterAutoImports, "@vueuse/core", "pinia"],
            dirs: [
                "./src/composables/",
                "./src/plugins/*/composables/*",
                "./src/utils/"
            ],
            vueTemplate: true,

            ignore: ["useCookies", "useStorage"],

            dts: "auto-imports.d.ts",

            eslintrc: {
                enabled: true,
                filepath: "./.eslintrc-auto-import.json",
                globalsPropValue: "readonly",
            },
        }),

        tailwindcss(),
    ],

    base: "/",

    resolve: {
        alias: {
            "@": path.resolve(__dirname, "./src"),
        },
    },

    server: {
        fs: {
            strict: true,
        },
        port: 5173,
    },

    build: {
        sourcemap: false,
        minify: "esbuild",
        target: "esnext",
        outDir: "dist",
        assetsDir: "assets",
        chunkSizeWarningLimit: 5000,
    },

    optimizeDeps: {
        entries: ["./src/**/*.vue"],
    },
});
