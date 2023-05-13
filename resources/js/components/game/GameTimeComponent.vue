<template>
    <div
        class="position-absolute top-0 d-flex justify-items-center justify-content-center time-count-container rounded-5 mt-5">
        {{ time }}
    </div>
</template>

<script>
import {addListener, getCurrentState} from "../../game/events";

export default {
    name: "GameTimeComponent",
    data() {
        return {
            seconds: 0,
            time: '00:00',
            startTime: 0,
            interval: null,
        }
    },
    mounted() {
        const state = getCurrentState();

        if (state.game_started) {
            this.init(state);
        } else {
            addListener(this.eventHandle);
        }
    },
    methods: {
        init(state) {
            this.startTime = state.start_time;
            this.startTimer();
        },
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
        },
        eventHandle(event, state) {
            switch (event) {
                case 'started':
                    this.init(state);
                    break;
            }
        },
    }
}
</script>

<style scoped>
.time-count-container {
    left: calc(50% - 60px);
    width: 120px;
    background: white;
    z-index: 10;
    color: #1C00A9;
    font-weight: bold;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
    font-size: 2rem;
}
</style>
