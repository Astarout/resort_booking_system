import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                /* css */
                'resources/css/app.css',
                'resources/css/password.css',
                /* js */
                'resources/js/app.js',
                /* landing */
                'resources/js/landing/aos.js',
                'resources/js/landing/myFlatPickr.js',
                'resources/js/landing/mySwiper.js',
                'resources/js/landing/reviewSwiper.js',
                'resources/js/landing/viewDates.js',
                /* support */
                'resources/js/support/clock.js',
                /* user */
                'resources/js/user/calendar.js',
                /* admin */
                'resources/js/admin/calendar.js',
                'resources/js/admin/graph.js',
                'resources/js/admin/stats.js',
                /* documents */
                'resources/js/documents/graphExport.js',
                'resources/js/documents/reservationExport.js',
                'resources/js/documents/salesReportExport.js',
                'resources/js/documents/transactionExport.js',
            ],
            refresh: true,
        }),
    ],
});
