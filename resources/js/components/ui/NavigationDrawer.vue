<template>
    <div class="d-lg-none">
        <v-navigation-drawer
            absolute
        >
            <nav-list></nav-list>
        </v-navigation-drawer>
    </div>
</template>

<script>
import NavList from "./NavList.vue";

export default {
    name: "NavigationDrawer",
    components: {NavList}
}
</script>

<style scoped>

</style>
