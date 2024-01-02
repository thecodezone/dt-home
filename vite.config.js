import { v4wp } from '@kucrut/vite-for-wp';

export default {
    plugins: [
        v4wp({
            input: {
                'plugin': 'resources/js/plugin.js',
                'admin': 'resources/js/admin.js',
            },
            outDir: 'dist', // Optional, defaults to 'dist'.
        }),
    ],
};