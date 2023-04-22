<template>
    <v-list
        rounded="rounded-lg"
        nav
    >
        <v-list-item
            v-for="(item, i) in navigations"
            :title="item.title"
            :prepend-icon="item.icon"
            :key="i"
            :value="item.value"
            :active="$route.params.id === $store.state.user.user.id"
            :to="item.link"
        >

        </v-list-item>
    </v-list>
</template>

<script>
export default {
    name: "NavList",
    data() {
        return {
            navigations: [
                {
                    title: 'Моя страница',
                    icon: 'mdi-home',
                    value: 'profile',
                    link: '/info/personal/' + this.$store.state.user.user.id
                },
                {
                    title: 'В игру',
                    icon: 'mdi-gamepad-variant',
                    value: 'game',
                    link: '/',
                },
                {
                    title: 'Статистика',
                    icon: 'mdi-view-dashboard',
                    value: 'stats',
                    link: '/info/personal/stats'
                },
                {
                    title: 'Настройки профиля',
                    icon: 'mdi-cog',
                    value: 'settings',
                    link: '/info/personal/settings'
                },
                {
                    title: 'Звуки',
                    icon: 'mdi-music-note',
                    value: 'settings',
                    link: '/info/sounds/without'
                },
                {
                    title: 'Звуки на события',
                    icon: 'mdi-music-note',
                    value: 'settings',
                    link: '/info/sounds/with'
                }
            ],
        };
    },
    methods: {
    }
}
</script>

<style scoped>

</style>
