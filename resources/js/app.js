import {createApp} from "vue";
import GameIndexComponent from "./components/game/GameIndexComponent.vue";

import {createVuetify} from "vuetify";

import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css'
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import {aliases, mdi} from 'vuetify/iconsets/mdi';
import router from "./router/router";
import App from "./components/App.vue";
import store from "./store";

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        }
    }
});

const app = createApp({
    components: {
        App
    }
}).use(vuetify)
    .use(router)
    .use(store)
    .mount('#app');

import './bootstrap';


