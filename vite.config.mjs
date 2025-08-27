import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // <-- permite conexiones externas (desde el host o navegador)
        port: 5173,       // <-- asegúrate de exponer este puerto en Docker
        hmr: {
            host: 'localhost', // o usa la IP de tu máquina host si no estás usando localhost
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});