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
                "resources/css/dashboard/dashboard-admin.css",
                "resources/js/dashboard/dashboard-admin.js",

                "resources/css/dashboard/dashboard-agent.css",
                "resources/js/dashboard/dashboard-agent.js",

                "resources/css/dashboard/dashboard-user.css",
                "resources/js/dashboard/dashboard-user.js",

                // Admin
                "resources/css/views/admin/categories.css",
                "resources/js/views/admin/categories.js",

                "resources/css/views/admin/departments.css",
                "resources/js/views/admin/departments.js",

                "resources/css/views/admin/users.css",
                "resources/js/views/admin/users.js",

                // Ticket
                "resources/css/views/ticket.css",
                "resources/js/views/ticket.js",

                // Report
                "resources/css/views/report.css",
                "resources/js/views/report.js",
            ],
            refresh: true,
        }),
    ],
});
