<template>
    <versus-component
    ></versus-component>

    <fight-component>
    </fight-component>

</template>

<script>
import VersusComponent from "./VersusComponent.vue";
import FightComponent from "./FightComponent.vue";

export default {
    name: "GameModeComponent",
    components: {
        FightComponent,
        VersusComponent
    }
};
</script>

<style scoped>

</style>
