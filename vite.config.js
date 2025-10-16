import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',       // 👈 bind to all interfaces
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',   // 👈 browser connects using localhost
            protocol: 'ws',
        },
    },
});
