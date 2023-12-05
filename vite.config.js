import { v4wp } from '@kucrut/vite-for-wp';

export default {
    plugins: [
        v4wp( {
            input: 'resources/js/plugin.js', // Optional, defaults to 'src/main.js'.
            outDir: 'dist', // Optional, defaults to 'dist'.
        } ),
    ],
};