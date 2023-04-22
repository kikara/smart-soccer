<template>
    <layout-content>
            <v-sheet
                elevation="3"
                rounded="0"
            >
                <div class="banner"></div>
                <div class="d-flex flex-column">
                    <div class="position-relative" style="height: 105px">
                        <div class="position-absolute left-0 d-flex px-2 gap-3" style="top: -50px">
                            <img :src="user.avatar_path" alt="" width="150" class="rounded-circle">
                        </div>
                        <div style="margin-left: 170px" class="pt-1">
                            <div class="d-flex justify-content-between pt-2 pb-2 pe-2 align-items-center flex-wrap">
                                <h3 class="user-header">{{ user.login }}</h3>
                                <v-btn
                                    class="btn-component rounded-xl"
                                    prepend-icon="mdi-sword"
                                    elevation="3"
                                    v-if="$store.state.user.user.id != $route.params.id"
                                >
                                    Бросить вызов
                                </v-btn>
                            </div>
                        </div>
                    </div>
                </div>
            </v-sheet>

            <v-sheet
                class="mt-2 p-0"
                style="background-color: transparent"
            >
                <div class="row pt-2 ms-0 me-0 gap-3">
                    <div class="col-md mb-2 p-0" v-for="game in lastGames">
                        <v-card
                            elevation="3"
                            rounded="0"
                        >
                            <template v-slot:subtitle>
                                <div class="text-center text-h6">{{ game.date }}</div>
                            </template>

                            <template v-slot:text>
                                <div class="d-flex justify-content-between">

                                    <div>
                                        <v-avatar
                                            :image="game.gamers[0].avatar_path"
                                            size="70"
                                            :to="'/info/personal/' + game.gamers[0].id"
                                        >
                                        </v-avatar>
                                    </div>

                                    <h1 class="align-items-center d-flex">
                                        {{ game.scores.map((item) => item.score).join(' - ') }}
                                    </h1>

                                    <div>
                                        <v-avatar
                                            :image="game.gamers[1].avatar_path"
                                            size="70"
                                        >
                                        </v-avatar>
                                    </div>
                                </div>
                            </template>
                        </v-card>
                    </div>
                </div>
            </v-sheet>

    </layout-content>
</template>

<script>
import LayoutContent from "../ui/LayoutContent.vue";

export default {
    name: "ProfileComponent",
    data() {
        return {
            user: {
                id: 0,
                login: '',
                avatar: '/images/user/man.png'
            },
            lastGames: []
        }
    },
    created() {
        const id = this.$route.params.id;

        axios.get(`/api/users/${id}`)
            .then((response) => {
                this.user = response.data.data;
            });

        axios.get(`/api/users/${id}/games`)
            .then((response) => {
                this.lastGames = response.data.data.map((item) => {
                    const fullDate = item.date.split(' ');
                    item.date = fullDate[0];
                    item.time = fullDate[1];
                    return item;
                });
            });
    },
    methods: {
        userScore(scores, id) {
            return scores.filter((item) => item.id === id)[0].score;
        },
    },
    components: {
        LayoutContent,
    }
}
</script>

<style scoped>

.banner {
    height: 125px;
}
.banner {
    background: url('/images/soccer_ball.jpg') repeat;
    background-size: cover;
}

.user-header {
    font-family: 'Teko', sans-serif;
    font-weight: 700;
    font-size: 40px;
    margin-bottom: 0;
}
</style>
