<template>
    <div :class="{'un-visible': visible}">
        <img class="background" alt="back" src="/images/game/soccer_back.png"/>
        <!--  ScoreBoard  -->
        <div
            class="position-absolute d-flex top-0 start-0 w-100 justify-content-center align-items-center z-index-5 min-vh-100"
            style="min-height: 100vh;">
            <div class="media-center-row">
                <div class="d-flex flex-column align-items-center" v-for="gamer in gamers">
                    <span class="scoreboard text-center">{{ gamer.score }}</span>
                </div>
            </div>
        </div>
        <!--  Progress line  -->
        <div class="w-100 position-absolute start-0 top-0 p-4 z-index-5">
            <div class="row gap-1">
                <div class="col">
                    <div class="d-flex">
                        <img src="/images/user/man.png" alt="" width="150">
                        <div class="w-100" style="padding-top: 2.5rem">
                            <v-progress-linear
                                color="red"
                                height="30"
                                reverse="reverse"
                                v-model="gamers[0].progress"
                            ></v-progress-linear>
                            <div class="user-login">{{ gamers[0].name }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto position-relative">
                    <div
                        class="position-absolute top-0 d-flex justify-items-center justify-content-center rounded-2 time-count-container">
                        <div>
                            {{ time }}
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <div class="w-100" style="padding-top: 2.5rem;">
                            <v-progress-linear
                                color="red"
                                height="30"
                                v-model="gamers[1].progress"
                            ></v-progress-linear>
                            <div class="text-end user-login">{{ gamers[1].name }}</div>
                        </div>
                        <img src="/images/user/man.png" alt="" width="150">
                    </div>
                </div>
            </div>
        </div>
        <!--  Control buttons  -->
        <div
            class="position-absolute w-100 start-0 top-0 d-flex justify-content-center align-items-center flex-column min-vh-100">
            <div class="d-flex flex-column gap-5 z-index-5 w-min-content">
                <v-btn block rounded="lg" size="x-large">Отмена последнего гола</v-btn>
                <v-btn block rounded="lg" size="x-large">Завершить игру</v-btn>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "GameComponent",
    props: ['gamers'],
    data() {
        return {
            seconds: 0,
            time: '00:00',
            visible: true
        }
    },
    methods: {
        start() {
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
        },
    },
    mounted() {
        setTimeout(() => this.visible = false, 500);
    }
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
    font-family: 'Teko', cursive;
    font-size: 15rem;
    font-weight: 900;
    color: #1C00A9;
}

.user-login {
    font-family: 'Rubik Wet Paint', cursive;
    color: #1C00A9;
    font-size: 1.5rem;
}

.time-count-container {
    left: -60px;
    width: 150px;
    background: white;
    z-index: 10;
    color: #1C00A9;
    font-weight: bold;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
    font-size: 2rem;
}

.z-index-5 {
    z-index: 5;
}

.w-min-content {
    width: min-content;
}

.un-visible {
    visibility: hidden;
}
</style>
