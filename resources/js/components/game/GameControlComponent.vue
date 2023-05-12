<template>
    <div
        class="position-absolute w-100 start-0 bottom-0 d-flex justify-content-center align-items-center min-vh-100">
        <div class="restore-container">
            <v-icon
                @click="resetLastGoal"
                rounded="circle"
                icon="mdi-arrow-left-circle"
                class="restore-icon rounded-circle"
                color="#04138b"
            >
            </v-icon>
        </div>
    </div>
</template>

<script>
export default {
    name: "GameControlComponent",
    methods: {
        resetLastGoal() {
            rws.send(JSON.stringify({cmd: 'resetLastGoal'}));
        },
    }
}
</script>

<style scoped>
.restore-container {
    z-index: 10;
    font-size: 5rem;
}

.restore-icon {
    background: whitesmoke;
    box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
}
</style>
