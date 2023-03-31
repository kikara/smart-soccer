<template>
    <div class="justify-content-center d-flex align-items-center py-5">
        <h1>Стол свободен</h1>
    </div>
    <div class="container table-responsive">
        <table class="table text-center caption-top">
            <caption>Последние игры</caption>
            <thead>
            <tr>
                <th>Дата</th>
                <th>Игроки</th>
                <th>Время</th>
                <th>Счет</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="game in games">
                <td>{{ game.date }}</td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <span>{{ game.gamers_versus[0] }}</span>
                        <v-icon icon="mdi-sword-cross"/>
                        <span>{{ game.gamers_versus[1] }}</span>
                    </div>
                </td>
                <td>{{ game.total_time }}</td>
                <td>{{ game.count }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import {mdiSwordCross} from "@mdi/js";

export default {
    name: "LastGamesComponent",
    data() {
        return {
            isMobile: false,
            games: null,
            mdiSwordCross,
        }
    },
    created() {
        axios.get('/games')
            .then((response) => {
                this.games = response.data.data;

                for (const game of this.games) {
                    const counts = {};
                    for (const round of game.rounds) {
                        for (const score of round.scores) {
                            let is = counts[score.user_id] ?? 0;
                            counts[score.user_id] = score.score >= 10 ? is + 1 : is;
                        }
                    }
                    game.count = Object.values(counts).join(' : ');
                    game.gamers_versus = game.gamers.map((item) => item.login);
                }
            });
    },
    mounted() {

    },
    methods: {
        onResize() {
            this.isMobile = window.innerWidth < 769
        }
    }
}
</script>

<style scoped>

</style>
