<template>
    <v-btn
        icon="mdi-play"
        @click="play"
        elevation="0"
    >
    </v-btn>
</template>

<script>
export default {
    name: "AudioButton",
    props: ['path'],
    methods: {
        play() {
            (new Audio(this.path)).play();
        },
    }
}
</script>

<style scoped>

</style>
