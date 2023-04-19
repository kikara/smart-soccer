<template>
    <router-view></router-view>
</template>

<script>
export default {
    name: "App",
    props: ['isAuth'],
    created() {
        if (this.isAuth) {
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
