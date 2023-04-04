<template>
    <div :class="{ 'd-none': events.gameMode }">
        <navigation-component :is-auth="isAuth"></navigation-component>
        <last-games-component></last-games-component>
    </div>
    <versus-component v-if="events.gameMode" :start-status="events.startStatus" :gamers="gamers"></versus-component>
    <game-component v-if="events.gameMode" :gamers="gamers"></game-component>
</template>

<script>
import NavigationComponent from "./NavigationComponent.vue";
import LastGamesComponent from "./LastGamesComponent.vue";
import VersusComponent from "./VersusComponent.vue";
import GameComponent from "./GameComponent.vue";
import GameSocket from "../game/socket";
import EventListener from "../game/EventListener";

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
            gamers: [],
            eventListener: EventListener()
        }
    },
    methods: {
        handle(e) {
            const json = JSON.parse(e.data);
            this.eventListener.handle(json);
        },
        eventHandle(event, state) {
            const callbacks = {
                booked: this.booked,
                started: this.started,
                goal: this.goal,
                new_round: this.newRound,
                game_over: this.gameOver,
            };
            callbacks[event](state);
        },
        async booked(state) {
            await this.setGamers(state);
            this.events.gameMode = true;
        },
        async setGamers(state) {
            for (const item of Object.values(state.round.gamers)) {
                axios.get('/users/' + item.user_id).then((response) => {
                    const data = response.data.data;
                    const progress = Number((10 - item.score) * 10);
                    this.gamers.push({
                        id: data.id,
                        name: data.login,
                        avatar: data.avatar,
                        score: item.score,
                        progress: progress,
                    });
                });
            }
        },
        started(state) {
            this.events.startStatus = true;
        },
        goal(state) {
            for (const gamer of this.gamers) {
                const gamerState = Object.values(state.round.gamers).filter((user) => user.user_id === gamer.id)[0];
                if (gamerState) {
                    gamer.score = Number(gamerState.score);
                }
            }
            this.gamers[0].progress = this.progress(this.gamers[1].score);
            this.gamers[1].progress = this.progress(this.gamers[0].score);
        },
        newRound(state) {
            this.goal(state);
        },
        gameOver(state) {

        },
        progress(value) {
            return (10 - value) * 10;
        },
    },
    mounted() {
        const socket = GameSocket();
        socket.addEventListener(this.handle)
        this.eventListener.addListener(this.eventHandle);
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
