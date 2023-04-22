import GameIndexComponent from "../components/game/GameIndexComponent.vue";
import ProfileComponent from "../components/profile/ProfileComponent.vue";
import {createRouter, createWebHistory} from "vue-router";
import Statistic from "../components/pages/Statistics.vue";
import Setting from "../components/pages/Settings.vue";
import Sounds from "../components/pages/Sounds.vue";
import EventSounds from "../components/pages/EventSounds.vue";


const routes = [
    {
        path: '/',
        component: GameIndexComponent
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




