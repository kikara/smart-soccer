<template>
    <gamer-progress-component></gamer-progress-component>

    <score-board-component></score-board-component>

    <game-time-component :time="time"></game-time-component>

    <game-control-component
        @resetLast=""
    ></game-control-component>

    <img class="background soccer-back" src="../../images/game/back.png" alt=""/>

    <div class="background game-bg"></div>
</template>

<script>
import ScoreBoardComponent from "./ScoreBoardComponent.vue";
import GamerProgressComponent from "./GamerProgressComponent.vue";
import GameTimeComponent from "./GameTimeComponent.vue";
import GameControlComponent from "./GameControlComponent.vue";

export default {
    name: "FightComponent",
    data() {
        return {
            seconds: 0,
            time: '00:00',
            visible: true
        }
    },
    methods: {
        startTimer() {
            const now = Math.floor(new Date().valueOf() / 1000);
            this.seconds = now - this.startTime;
            this.interval = setInterval(this.timer, 1000);
        },
        timer() {
            this.seconds++;

            const minutes = Math.floor(this.seconds / 60);
            const seconds = this.seconds - (minutes * 60);

            this.time = (minutes > 9 ? minutes : '0' + minutes) + ':' + (seconds > 9 ? seconds : '0' + seconds);
        },
        stop() {
            clearInterval(this.interval);
        }
    },
    watch: {
        start(val) {
            if (val) {
                this.startTimer();
            }
        },
    },
    mounted() {
        if (this.start) {
            this.startTimer();
        }
    },
    components: {GameControlComponent, GameTimeComponent, GamerProgressComponent, ScoreBoardComponent},
}

</script>

<style scoped>

.game-bg {
    background-color: #0093E9;
    background-image: linear-gradient(160deg, #0093E9 0%, #80D0C7 100%);
}

.soccer-back {
    z-index: 1;
    height: 70vh!important;
}

.background {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100vh;
}
</style>
