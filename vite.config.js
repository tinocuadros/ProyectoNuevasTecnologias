import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
<<<<<<< HEAD
import tailwindcss from '@tailwindcss/vite';
=======
import react from "@vitejs/plugin-react";


>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8

export default defineConfig({
    plugins: [
        laravel({
<<<<<<< HEAD
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
=======
            input: ['resources/js/app.jsx'],
            refresh: true,
        }),
        react()
    ],
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
});
