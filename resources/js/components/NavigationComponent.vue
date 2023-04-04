<template>
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand" href="">
                    Умный настольный футбол
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a href="/game" class="nav-link">Игра</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">Турниры</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">Статистика</a>
                        </li>
                        <!-- Authentication Links -->

                        <li class="nav-item" v-if="!isAuth">
                            <a class="nav-link" href="">Войти</a>
                        </li>



                        <li class="nav-item" v-if="!isAuth">
                            <a class="nav-link" href="">Зарегистрироваться</a>
                        </li>

                        <li class="nav-item dropdown" v-if="isAuth">
                            <div id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <v-avatar image="/images/user/man.png"></v-avatar>
                            </div>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="/users/profile">
                                        Личный кабинет
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/logout"
                                       onclick="">
                                        Выйти
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
</template>

<script>
export default {
    name: "NavigationComponent",
    props: ['isAuth'],
    data() {
        return {
            test: 0,
            authenticated: Boolean(this.isAuth)
        }
    },
}
</script>

<style scoped>

</style>
