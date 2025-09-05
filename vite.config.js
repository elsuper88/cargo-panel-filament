import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        // Optimizaciones para Electron
        target: 'esnext',
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
    optimizeDeps: {
        include: [],
    },
    define: {
        // Definir variables globales para el fix de NativePHP
        'window.isNode': false,
        'global': 'window',
    },
});
