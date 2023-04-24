import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vuetify from "vite-plugin-vuetify";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.scss',
            'resources/js/app.js'
        ]),
        vue(),
        vuetify()
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js'
        }
    }
});
