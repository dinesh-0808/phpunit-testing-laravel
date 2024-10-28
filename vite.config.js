import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Listen on all network interfaces
        port: 3000,      // Optionally set a specific port, e.g., 3000
        hmr: {
            host: '192.168.2.141', // You can set this to your local IP to ensure HMR works correctly across devices
        },
    },
});
