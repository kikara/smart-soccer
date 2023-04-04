<template>
    <div>
        <div v-for="side in sides" class="versus z-index-100" :class="[
                side,
                {
                    'versus-close-state animation-versus-close': !startStatus,
                    'versus-open-state animation-versus-open': startStatus,
                }
            ]">
        </div>

        <!--  Versus Image  -->
        <div class="position-absolute top-0 start-0 w-100 d-flex flex-column justify-content-center un-visible min-vh-100 z-index-100"
             :class="{ 'appearance': appearance && !startStatus }"
             >
            <img src="/images/game/versus.png" style="width: 10rem" class="align-self-center" alt=""/>
        </div>

        <!--  Gamers  -->
        <div :class="{ 'appearance': appearance && !startStatus }"
             class="position-absolute d-flex top-0 start-0 w-100 justify-content-center align-items-center un-visible min-vh-100 z-index-100"
             >

            <div class="media-center-row">
                <div class="d-flex flex-column align-items-center" v-for="gamer in gamers">
                    <img :src="gamer.avatar" alt="avatar" width="150">
                    <span class="user-login text-center">{{ gamer.name }}</span>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "VersusComponent",
    props: ['startStatus', 'gamers'],
    data() {
        return {
            sides: [
                'red-side',
                'blue-side'
            ],
            appearance: false
        }
    },
    mounted() {
        setTimeout(() => {
            this.appearance = true;
        }, 350);
    }

}
</script>

<style scoped>
.z-index-100 {
    z-index: 100;
}
.un-visible {
    opacity: 0;
    visibility: hidden;
    transition: opacity .3s ease-in-out;
}

.appearance {
    opacity: 1;
    visibility: visible;
    transition: opacity .3s ease-in-out;
}

.user-login {
    font-family: 'Rubik Wet Paint', cursive;
    font-weight: 400;
    font-size: 75px;
    color: #eeef46;
}
</style>
