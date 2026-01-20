import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/editorJs/field.css',
                'resources/js/app.js',
                'resources/js/editorJs/field.js',
                'resources/js/editorJs/config.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
