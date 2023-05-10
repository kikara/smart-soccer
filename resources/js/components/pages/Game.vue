<template>
    <component
        :is="component"
        @switch="setHome"
    ></component>
</template>

<script>
import {addListener, getCurrentState} from "../../game/events";
import HomeComponent from "../game/HomeComponent.vue";
import GameModeComponent from "../game/GameModeComponent.vue";

export default {
    name: "Game",
    data() {
        return {
            component: 'HomeComponent',
            canHandle: true
        }
    },
    created() {
        addListener(this.eventHandle);

        rws.send(JSON.stringify({cmd: 'state'}));
    },
    methods: {
        setHome() {
            this.component = 'HomeComponent'
            this.canHandle = true;
        },
        eventHandle(event, state) {

            if (!this.canHandle) {
                return;
            }

            this.canHandle = false;

            switch (event) {
                case 'updated':
                    if (state.is_busy) {
                        this.bookedState(state);
                    }
                    break;
                default:
                    this.canHandle = true;
            }
        },
        async bookedState(state) {

            await this.setGamers(state);

            this.component = 'GameModeComponent';
        },
        async setGamers(state) {
            for (const item of Object.values(state.round.gamers)) {
                await axios.get('/api/users/' + item.user_id)
                    .then(response => {
                        const data = response.data.data;

                        this.$store.commit('pushGamer', {
                            id: data.id,
                            name: data.login,
                            avatar: data.avatar_path,
                            score: item.score,
                            progress: 100,
                            rounds: 0
                        });
                    });
            }
        },
    },
    components: {
        HomeComponent,
        GameModeComponent
    }
};
</script>

<style scoped>

</style>
