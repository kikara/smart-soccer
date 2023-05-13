import {createApp} from "vue";

import {createVuetify} from "vuetify";

import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css'
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import {aliases, mdi} from 'vuetify/iconsets/mdi';
import router from "./router/router";
import App from "./components/App.vue";
import store from "./store";
import './game/socket.js';
import {handle} from "./game/events";

rws.onmessage = (event) => {
    const json = JSON.parse(event.data);
    handle(json);
}

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


