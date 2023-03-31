// require('bootstrap');
require('./bootstrap');
require('./config');

import {createApp} from "vue";
import GameIndexComponent from "./components/GameIndexComponent.vue";
import NavigationComponent from "./components/NavigationComponent.vue";
import GameComponent from "./components/GameComponent.vue";

//Vuetify
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';
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
        GameIndexComponent,
        GameComponent
    }
}).use(vuetify)
    .mount('#app');



