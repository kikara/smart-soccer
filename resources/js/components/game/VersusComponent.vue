<template>
    <div>
        <transition name="gate-left">
            <div v-if="gates" class="versus versus-close-state z-index-100 blue-side"></div>
        </transition>

        <transition name="gate-right">
            <div v-if="gates" class="versus versus-close-state z-index-100 red-side"></div>
        </transition>

        <transition name="versus">
            <!--  Versus Image  -->
            <div v-if="info"
                 class="position-absolute top-0 start-0 w-100 d-flex align-items-center justify-content-center min-vh-100" style="z-index: 150">
                <div style="max-width: 250px" class="d-flex flex-column gap-4">
                    <img src="../../images/game/versus.png" width="150" class="align-self-center" alt=""/>
                </div>
            </div>
        </transition>

        <transition name="info">
            <!--  Gamers  -->
            <div v-if="info"
                class="position-absolute d-flex top-0 start-0 w-100 justify-content-center align-items-center min-vh-100 z-index-100">
                <div class="media-center-row">
                    <div class="d-flex flex-column align-items-center" v-for="gamer in $store.state.game.gamers">
                        <v-avatar
                            :image="gamer.avatar"
                            size="200"
                        >

                        </v-avatar>
                        <span class="user-login text-center">{{ gamer.name }}</span>
                    </div>
                </div>
            </div>
        </transition>

        <div class="position-absolute bottom-0 end-0">
            <div
                class="start-button position-relative"
                v-if="info"
            >
                <v-icon
                    @click="start"
                    icon="mdi-play-circle"
                    color="#04138b"
                    class="rounded-circle p-2 play-icon"
                ></v-icon>
            </div>
        </div>

    </div>
</template>

<script>
import {addListener, getCurrentState} from "../../game/events";

export default {
    name: "VersusComponent",
    data() {
        return {
            info: false,
            gates: false,
            stateClosed: false,
        }
    },
    mounted() {
        const state = getCurrentState();

        if (!state.game_started) {
            this.gates = true;
            setTimeout(() => this.info = true, 400);
            addListener(this.eventHandle);
        }
    },
    methods: {
        start() {
            rws.send(JSON.stringify({cmd: 'start'}));
        },
        eventHandle(event, state) {
            switch (event) {
                case 'started':
                    this.info = false;
                    this.gates = false;
                    break;
            }
        },
    }
}
</script>

<style scoped>

.info-enter-active, .versus-enter-active {
    transition: all 1s ease-out;
}

.info-enter-from, .versus-enter-from,
.info-leave-to, .versus-leave-to {
    opacity: 0;
}

.versus {
    position: absolute;
    animation-duration: .2s;
    animation-timing-function: cubic-bezier(0.1, -0.6, 0.2, 0);
    min-height: 100vh;
}

.blue-side {
    top: 0;
    left: 0;
    background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(37,145,251,0.98) 0.1%, rgba(0,7,128,1) 99.8% );
}

.red-side {
    background-image: linear-gradient( 69.7deg,  rgba(216,81,82,1) 40%, rgba(154,27,69,1) 100.1% );
}

.z-index-100 {
    z-index: 100;
}

.gate-left-enter-active, .gate-right-enter-active {
    animation-name: versus-close;
}

.gate-left-leave-active, .gate-right-leave-active {
    animation-name: versus-open;
}


@media (min-width: 992px) {
    .versus-close-state {
        width: 50%;
        min-height: 100vh;
    }
    .red-side {
        top: 0;
        right: 0;
    }
    @keyframes versus-close {
        0% {
            width: 0;
        }
        100% {
            width: 50%;
        }
    }
    @keyframes versus-open {
        0% {
            width: 50%;
        }
        100% {
            width: 0;
        }
    }
}

@media(min-width: 0px) and (max-width: 992px) {
    .versus-close-state {
        width: 100%;
        min-height: 50vh;
    }
    .red-side {
        bottom: 0;
        left: 0;
    }
    @keyframes versus-close {
        0% {
            min-height: 0;
        }
        100% {
            min-height: 50vh;
        }
    }
    @keyframes versus-open {
        0% {
            min-height: 50vh;
        }
        100% {
            min-height: 0;
        }
    }
}

.user-login {
    font-family: 'Rubik Wet Paint', cursive;
    font-weight: 400;
    font-size: 75px;
    color: #eeef46;
}

.font-primary {
    font-family: 'Rubik Wet Paint', cursive;
    color: lightyellow;
}

.start-button {
    z-index: 200;
    font-size: 6rem;
    margin-right: 2rem;
    margin-bottom: 1rem;
}

.play-icon {
    background: whitesmoke;
    box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
}

</style>
