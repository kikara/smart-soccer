<template>
    <gamer-progress-component></gamer-progress-component>

    <score-board-component></score-board-component>

    <game-time-component></game-time-component>

    <game-control-component></game-control-component>

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
    methods: {

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
