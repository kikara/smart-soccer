<template>
    <component
        :is="component"
    ></component>
</template>

<script>
import {addListener} from "../../game/events";
import HomeComponent from "../game/HomeComponent.vue";
import GameModeComponent from "../game/GameModeComponent.vue";
import {booked, handleEvent, reset} from "../../game/audios";
export default {
    name: "Game",
    data() {
        return {
            component: null,
        }
    },
    mounted() {
        if (rws.readyState === rws.CLOSED) {
            this.component = 'HomeComponent';
        }

        addListener(this.eventHandle);

        rws.send(JSON.stringify({cmd: 'state'}));
    },
    unmounted() {
        reset();
    },
    methods: {
        eventHandle(event, state) {
            handleEvent(event, state);
            switch (event) {
                case 'updated':
                    if (state.is_busy && !state.game_over) {
                        this.setGameMode(state);
                    } else {
                        this.setHome();
                    }
                    break;
            }
        },
        async setGameMode(state) {

            if (this.component === 'GameModeComponent') {
                return;
            }

            await this.$store.commit('reset');

            await this.setGamers(state);

            await booked(state);

            this.component = 'GameModeComponent';
        },
        setHome() {
            if (this.component === 'HomeComponent') {
                return;
            }

            this.component = 'HomeComponent';

            this.$store.commit('reset');
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
