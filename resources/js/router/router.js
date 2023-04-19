import GameIndexComponent from "../components/game/GameIndexComponent.vue";
import ProfileComponent from "../components/profile/ProfileComponent.vue";
import {createRouter, createWebHistory} from "vue-router";


const routes = [
    {
        path: '/',
        component: GameIndexComponent
    },
    {
        path: '/info/personal/:id',
        component: ProfileComponent
    }
];

const router = createRouter({
    routes,
    history: createWebHistory(''),
});

export default router;




