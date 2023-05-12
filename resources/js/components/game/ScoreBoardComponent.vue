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
export default {
    name: "ScoreBoardComponent"
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
