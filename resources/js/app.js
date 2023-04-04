import {createApp} from "vue";
import GameIndexComponent from "./components/GameIndexComponent.vue";

import {createVuetify} from "vuetify";
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import {aliases, mdi} from 'vuetify/iconsets/mdi';

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases: {
            ...aliases,
        },
        sets: {
            mdi,
        }
    }
});

const app = createApp({
    components: {
        GameIndexComponent
    }
}).use(vuetify)
    .mount('#app');



