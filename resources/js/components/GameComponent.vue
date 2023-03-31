<template>
    <img class="background" alt="back" src="/images/game/soccer_back.png"/>
    <!--  ScoreBoard  -->
    <div class="position-absolute d-flex top-0 start-0 w-100 justify-content-center align-items-center"
         style="min-height: 100vh;z-index: 5">
        <div class="media-center-row">
            <div class="d-flex flex-column align-items-center" v-for="n in 2">
                <span class="scoreboard text-center">01</span>
            </div>
        </div>
    </div>
    <!--  Progress line  -->
    <div class="w-100 position-absolute start-0 top-0">
        <div class="row">
            <div class="col">
                <div class="row align-items-center">
                    <div class="col" style="">
                        <img src="/images/user/man.png" alt="" width="150">
                    </div>
                    <div class="col" style="height: 100px; background: #2c87ff"></div>
                </div>
            </div>
            <div class="col">
                <div class="row align-items-center">
                    <div class="col" style="height: 100px; background: #2c87ff"></div>
                    <div class="col" style="">
                        <img src="/images/user/man.png" alt="" width="150">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "GameComponent"
}
</script>

<style scoped>
.background {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    max-height: 100%;
}
.scoreboard {
    font-family: 'Big Shoulders Display', cursive;
    font-size: 20rem;
    font-weight: 900;
    color: #ffc352;
}
</style>
