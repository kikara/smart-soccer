<template>
    <router-view></router-view>
</template>

<script>
export default {
    name: "App",
    props: ['isAuth', 'userId'],
    created() {
        if (this.isAuth) {
            this.$store.commit('setUserId', this.userId);
            axios.get('/users')
                .then((response) => {
                    this.$store.commit('setAuthenticated');
                    this.$store.commit('setAuthenticatedUser', response.data.data);
                });
        }
    },
    mounted() {
    },
}
</script>

<style scoped>

</style>
