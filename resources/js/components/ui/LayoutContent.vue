<template>
    <v-app>
        <navigation-drawer></navigation-drawer>
        <v-main class="app-bg ps-0">
            <div class="container-lg">
                <div class="row mt-4">
                    <div class="col-lg-3">
                        <v-sheet
                            rounded="0"
                            class="d-none d-md-block"
                            elevation="3"
                        >
                            <nav-list></nav-list>
                        </v-sheet>
                    </div>
                    <div class="col-lg">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </v-main>
        <toast></toast>
    </v-app>
</template>

<script>
import NavList from "./NavList.vue";
import NavigationDrawer from "./NavigationDrawer.vue";
import Toast from "./Toast.vue";

export default {
    name: "LayoutContent",
    components: {Toast, NavigationDrawer, NavList}
}
</script>

<style scoped>
.app-bg {
    background-color: #21D4FD;
    background-image: linear-gradient(19deg, #21D4FD 0%, #B721FF 100%);
}
</style>
