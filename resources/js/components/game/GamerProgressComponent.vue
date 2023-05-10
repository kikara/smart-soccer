<template>
    <div class="w-100 position-absolute start-0 top-0 p-4 z-index-5">
        <div class="row gap-1 position-relative">
            <div class="col" v-for="(gamer, index) in $store.state.game.gamers" :key="gamer.id">
                <div class="d-flex gap-2" :class="{'flex-row-reverse': index === 1}">
                    <div
                        class="rounded-circle p-2 avatar-container"
                    >
                        <v-avatar
                            :image="gamer.avatar"
                            size="150"
                        >
                        </v-avatar>
                    </div>

                    <div class="w-100 pt-5">

                        <v-progress-linear
                            class="progress-score"
                            color="deep-purple-accent-4"
                            height="30"
                            v-model="gamer.progress"
                            :reverse="index === 0"
                        ></v-progress-linear>

                        <div class="d-flex justify-content-between mt-4"
                             :class="{'flex-row-reverse': index === 1}"
                        >
                            <div class="user-login"
                                 :class="{'ms-5': index === 0, 'me-5': index === 1}"
                            >
                                {{ gamer.name }}
                            </div>

                            <div>
                                <img class="mt-1" v-for="round in gamer.rounds" src="../../images/game/soccerball.svg" alt="soccer" width="30">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "GamerProgressComponent"
}
</script>

<style scoped>
.avatar-container {
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    background-color: #4158D0;
    background-image: linear-gradient(43deg, #4158D0 0%, #C850C0 46%, #FFCC70 100%);
}
.progress-score {
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
}
.user-login {
    color: whitesmoke;
    font-size: 2.5rem;
    font-weight: 800;
}
</style>
