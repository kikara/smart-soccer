<template>
    <div
        class="position-absolute rounded-0 text-center v-toast"
        v-if="visible"
    >
        <v-chip
            color="dark"
            size="x-large"
        >
            {{ message }}
        </v-chip>
    </div>
</template>

<script>
export default {
    name: "Toast",
    data() {
        return {
            visible: false,
            message: '',
        }
    },
    methods: {
        show() {
            this.message = this.$store.state.toast.message;
            this.visible = true;
            setTimeout(() => {
                this.visible = false;
                this.$store.commit('setDone');
            }, 2000);
        },
    },
    created() {
    },
    watch: {
        '$store.state.toast.visible': function (newValue, oldValue) {
            if (newValue) {
                this.show()
            }
        },
    },
    beforeDestroy() {
    }
};
</script>

<style scoped>
.v-toast {
    width: 200px;
    left: calc(50% - 100px);
    bottom: 10%;
}
</style>
