import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",

                // Authentication
                "resources/css/auth/auth.css",

                // Dashboard
                "resources/css/dashboard/dashboard.css",
                "resources/js/dashboard/dashboard.js",

                // Views
                "resources/css/views",
                "resources/js/views",
            ],
            refresh: true,
        }),
    ],
});
