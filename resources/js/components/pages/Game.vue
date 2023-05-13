<template>
    <component
        :is="component"
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
            component: null,
            fetched: false,
        }
    },
    mounted() {
        if (rws.readyState === rws.CLOSED) {
            this.component = 'HomeComponent';
        }

        addListener(this.eventHandle);

        rws.send(JSON.stringify({cmd: 'state'}));
    },
    methods: {
        setHome() {
            this.component = 'HomeComponent';
            this.fetched = false;
        },
        eventHandle(event, state) {
            switch (event) {
                case 'updated':
                    if (state.is_busy) {
                        this.bookedState(state);
                    }
                    break;
            }
        },
        async bookedState(state) {

            if (this.fetched) {
                return;
            }

            await this.$store.commit('reset');

            await this.setGamers(state);

            this.fetched = true;

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
