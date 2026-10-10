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
    css: {
        preprocessorOptions: {
            scss: {
                // SFC styles import partials as 'resources/sass/...' (relative to the project root)
                loadPaths: [import.meta.dirname],
                // Legacy SCSS partials still use @import and global color functions
                silenceDeprecations: ['import', 'global-builtin', 'color-functions'],
            },
        },
    },
    server: {
        // Inside Docker the dev server listens on 0.0.0.0; the browser must still reach it via localhost
        hmr: process.env.VITE_DEV_SERVER_HOST ? { host: process.env.VITE_DEV_SERVER_HOST } : undefined,
        watch: {
            ignored: ['**/vendor/**', '**/storage/**', '**/public/**', '**/tests/**'],
        },
    },
    define: {
        __VUE_I18N_FULL_INSTALL__: true,
        __VUE_I18N_LEGACY_API__: true,
        __INTLIFY_PROD_DEVTOOLS__: false,
    },
})
