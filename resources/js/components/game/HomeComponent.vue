<template>
    <navigation-component
    ></navigation-component>

    <last-games-component
    ></last-games-component>
</template>

<script>
import NavigationComponent from "./NavigationComponent.vue";
import LastGamesComponent from "./LastGamesComponent.vue";

export default {
    name: "HomeComponent",
    components: {LastGamesComponent, NavigationComponent}
}
</script>

<style scoped>

</style>
