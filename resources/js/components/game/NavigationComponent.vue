<template>
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">

                        <li class="nav-item" v-if="!$store.state.user.is_auth">
                            <a class="nav-link" href="/login">Войти</a>
                        </li>

                        <li class="nav-item" v-if="!$store.state.user.is_auth">
                            <a class="nav-link" href="">Зарегистрироваться</a>
                        </li>

                        <li class="nav-item" v-if="$store.state.user.is_auth">
                            <router-link :to="personalLink">
                                <v-avatar
                                    image="/images/user/man.png"
                                    size="50"
                                >

                                </v-avatar>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
</template>

<script>
export default {
    name: "NavigationComponent",
    data() {
        return {
            personal: ''
        }
    },
    computed: {
        personalLink() {
            return '/info/personal/' + this.$store.state.user.user.id;
        },
    }
}
</script>

<style scoped>

</style>
