<template>
    <layout-content>
        <v-sheet>
            <v-table
            >
                <thead class="text-center">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Игрок</th>
                    <th class="text-center">Побед</th>
                    <th class="text-center">Поражений</th>
                    <th class="text-center">win-rate</th>
                    <th class="text-center">Игр</th>
                    <th class="text-center">Рейтинг</th>
                </tr>
                </thead>
                <tbody class="text-center">
                <tr
                    v-for="(gamer, index) in gamers"
                    :key="gamer.id"
                >
                    <td>{{ index + 1 }}</td>
                    <td>
                        <router-link
                            :to="link(gamer.id)"
                        >
                            {{ gamer.name }}
                        </router-link>
                    </td>
                    <td>{{ gamer.win }}</td>
                    <td>{{ gamer.lose }}</td>
                    <td>{{ winRate(gamer) }}</td>
                    <td>{{ gamer.count }}</td>
                    <td>{{ gamer.rating }}</td>
                </tr>
                </tbody>
            </v-table>
        </v-sheet>
    </layout-content>
</template>

<script>
import LayoutContent from "../ui/LayoutContent.vue";

export default {
    name: "Statistic",
    data() {
        return {
            gamers: []
        }
    },
    mounted() {
        axios.get('/api/users/stats')
            .then(response => {
                this.gamers = response.data.data;
            });
    },
    methods: {
        winRate(gamer) {
            return (Math.floor((gamer.win / gamer.lose) * 100) / 100);
        },
        link(id) {
            return '/info/personal/' + id;
        },
    },
    components: {LayoutContent}
};
</script>

<style scoped>

</style>
