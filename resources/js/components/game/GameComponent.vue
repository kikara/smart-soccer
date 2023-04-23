<template>
    <div>
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
            <div class="row gap-1 position-relative">
                <div class="col" v-for="(gamer, index) in gamers" :key="gamer.id">
                    <div class="d-flex" :class="{'flex-row-reverse': index === 1}">
                        <img :src="gamer.avatar" alt="" width="150">
                        <div class="w-100 pt-3">
                            <v-progress-linear
                                color="red"
                                height="30"
                                v-model="gamer.progress"
                                :reverse="index === 0"
                            ></v-progress-linear>
                            <div class="d-flex justify-content-between"
                                 :class="{'flex-row-reverse': index === 1}"
                            >
                                <div class="user-login">{{ gamer.name }}</div>
                                <div>
                                    <img class="mt-1" v-for="round in gamer.rounds" src="/images/game/soccerball.svg" alt="soccer" width="30">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="position-absolute top-0 d-flex justify-items-center justify-content-center rounded-5 time-count-container">
                        {{ time }}
                </div>
            </div>
        </div>
        <!--  Control buttons  -->
        <div
            class="position-absolute w-100 start-0 top-0 d-flex justify-content-center align-items-center flex-column min-vh-100">
            <div class="d-flex flex-column gap-5 z-index-5 w-min-content">
                <v-btn block rounded="lg" size="x-large" @click="this.$emit('reset-last')"
                       elevation="0" style="font-size: 2rem;"
                >Отмена последнего гола</v-btn>
                <v-btn block rounded="lg" size="x-large" @click="this.$emit('end-game')"
                       elevation="0" style="font-size: 2rem;"
                >Завершить игру</v-btn>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "GameComponent",
    props: {
        gamers: {
            type: Array,
            required: true,
            default() {
                const defaultGamer = {
                    id: 0,
                    name: '',
                    avatar: '/images/user/man.png',
                    score: 0,
                    progress: 100,
                }

                const out = [];
                for (let i = 0; i < 2; i++) {
                    out.push(defaultGamer);
                }

                return out;
            }
        },
        start: {
            type: Boolean
        },
        startTime: {
            type: Number
        }
    },
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
    left: calc(50% - 75px);
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
</style>
