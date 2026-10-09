import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/main.js', 'resources/sass/app.scss', 'resources/css/tailwind.css'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    // Keep absolute URLs (e.g. /assets/...) pointing to the public directory
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
    resolve: {
        extensions: ['.mjs', '.js', '.mts', '.ts', '.jsx', '.tsx', '.json', '.vue'],
    },
})
