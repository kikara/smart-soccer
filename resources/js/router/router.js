import ProfileComponent from "../components/pages/Profile.vue";
import {createRouter, createWebHistory} from "vue-router";
import Statistic from "../components/pages/Statistics.vue";
import Setting from "../components/pages/Settings.vue";
import Sounds from "../components/pages/Sounds.vue";
import EventSounds from "../components/pages/EventSounds.vue";
import Game from "../components/pages/Game.vue";


const routes = [
    {
        path: '/',
        component: Game
    },
    {
        path: '/info/personal/:id',
        component: ProfileComponent
    },
    {
        path: '/info/personal/stats',
        component: Statistic
    },
    {
        path: '/info/personal/settings',
        component: Setting
    },
    {
        path: '/info/sounds/without',
        component: Sounds
    },
    {
        path: '/info/sounds/with',
        component: EventSounds,
    }
];

const router = createRouter({
    routes,
    history: createWebHistory(''),
});

export default router;




