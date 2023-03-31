<template>
    <div :class="{ 'd-none': events.gameMode }">
        <navigation-component :is-auth="isAuth"></navigation-component>
        <last-games-component ></last-games-component>
    </div>
    <versus-component v-if="events.gameMode" :start-status="events.startStatus"></versus-component>
    <game-component v-if="events.gameMode"></game-component>
</template>

<script>
import NavigationComponent from "./NavigationComponent.vue";
import LastGamesComponent from "./LastGamesComponent.vue";
import VersusComponent from "./VersusComponent.vue";
import GameComponent from "./GameComponent.vue";
export default {
    name: "GameIndex",
    props: ['isAuth'],
    data() {
        return {
            counter: 0,
            events: {
                gameMode: false,
                startStatus: false
            },
        }
    },
    created() {
        window.addEventListener('versus_status', () => {
            this.events.gameMode = !this.events.gameMode;
            this.events.startStatus = false;
        });
        window.addEventListener('start_status', () => {
            this.events.startStatus = true;
        });
    },
    mounted() {
        // setTimeout(() => {
        //     this.events.gameMode = true;
        // }, 300);
    },
    components: {
        GameComponent,
        VersusComponent,
        NavigationComponent,
        LastGamesComponent,
    }
}
</script>

<style scoped>

</style>
