<template>
    <div
        class="position-absolute d-flex top-0 start-0 w-100 justify-content-center align-items-center z-index-5 min-vh-100">
        <div class="media-center-row scoreboard">
            <div
                v-for="gamer in $store.state.game.gamers"
            >
                <div class="score align-self-center">
                        {{ gamer.score }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {addListener, getCurrentState} from "../../game/events";

export default {
    name: "ScoreBoardComponent",
    mounted() {
        const state = getCurrentState();

        if (state.game_started) {
            this.setScores(state);
        }

        addListener(this.eventHandle);
    },
    methods: {
        eventHandle(event, state) {
            switch (event) {
                case 'updated':
                    this.setScores(state);
                    break;
            }
        },
        setScores(state) {
            for (const gamer of this.$store.state.game.gamers) {
                const gamerState = Object.values(state.round.gamers).find(user => user.user_id === gamer.id);

                gamer.score = Number(gamerState.score);

                const enemyState = Object.values(state.round.gamers).find(user => user.user_id !== gamer.id);

                gamer.progress = this.progress(enemyState.score);

                this.$store.commit('update', gamer);
            }
        },
        progress(value) {
            return (10 - value) * 10;
        },
    }
}
</script>

<style scoped>
.scoreboard {
    font-family: 'Teko', cursive;
    font-size: 15rem;
    font-weight: 900;
    color: lightyellow;
    z-index: 5;
}
.score {
    height: 180px;
    line-height: 220px;
}
</style>
