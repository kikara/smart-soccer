<template>
    <v-list-item
        class="py-2"
        rounded="0"
    >

        <template v-slot:prepend>
            <audio-button
                :path="item.path"
            >

            </audio-button>
        </template>


        <template v-slot:append>
            <v-btn
                icon="mdi-delete"
                elevation="0"
                @click="$emit('destroy')"
            >

            </v-btn>
        </template>

        <div class="px-2">
            <div>{{ item.name }}</div>
            <slot></slot>
        </div>
    </v-list-item>
</template>

<script>
import AudioButton from "./AudioButton.vue";

export default {
    name: "AudioListItem",
    props: ['item'],
    components: {AudioButton}
}
</script>

<style scoped>

</style>
