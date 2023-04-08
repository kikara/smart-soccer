<template>
    <div>
        <navigation-component v-if="!events.gameMode" :is-auth="isAuth"></navigation-component>
        <last-games-component v-if="!events.gameMode"></last-games-component>
        <versus-component v-if="events.versus" :start="events.start" :gamers="gamers"></versus-component>
        <game-component
            v-if="events.gameMode"
            :gamers="gamers"
            :start="events.start"
            :start-time="events.startTime"
        ></game-component>
    </div>
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
                versus: false,
                start: false,
                startTime: 0,
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
            console.log(event);
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
            this.events.versus = true;
            setTimeout(() => this.events.gameMode = true, 450);
        },
        async setGamers(state) {
            for (const item of Object.values(state.round.gamers)) {
                axios.get('/users/' + item.user_id).then((response) => {
                    const data = response.data.data;
                    this.gamers.push({
                        id: data.id,
                        name: data.login,
                        avatar: data.avatar,
                        score: item.score,
                        progress: 100,
                        rounds: 0
                    });
                });
            }
        },
        started(state) {
            this.events.start = true;
            this.events.startTime = state.start_time;
            setTimeout(() => this.versus = false, 450);
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

            for (const gamer of this.gamers) {
                const isWinner = state.rounds.find(round => round.winner_id === gamer.id);
                gamer.rounds = isWinner ? 1 : 0;
            }
        },
        gameOver(state) {
            this.events.gameMode = false;
            this.events.start = false;
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
