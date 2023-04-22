<template>
    <layout-content>
        <v-sheet
            class="p-3"
        >
            <div class="text-h6">Добавить звук без параметров</div>
            <v-divider></v-divider>

            <v-form
                @submit.prevent="upload"
            >
                <v-file-input
                    acccept="audio/mpeg, audio/mp4, audio/ogg"
                    placeholder="Выбери звук"
                    prepend-icon="mdi-music-note"
                    label="Добавить"
                    variant="underlined"
                    v-model="file"
                    :loading="loading"
                >
                </v-file-input>
                <v-btn
                    prepend-icon="mdi-upload"
                    type="submit"
                    elevation="0"
                    rounded="0"
                >
                    Загрузить
                </v-btn>
            </v-form>

        </v-sheet>

        <v-sheet
            class="px-3 mt-2"
            v-if="audios.length !== 0"
        >
            <v-list>
                <audio-list-item
                    v-for="(audio, key) in audios"
                    :item="audio"
                    :key="key"
                    @destroy="destroy(audio.id)"
                >

                </audio-list-item>
            </v-list>
        </v-sheet>
    </layout-content>
</template>

<script>
import LayoutContent from "../ui/LayoutContent.vue";
import AudioButton from "../ui/AudioButton.vue";
import AudioListItem from "../ui/AudioListItem.vue";

export default {
    name: "Sounds",
    data() {
        return {
            file: [],
            loading: false,
            audios: [],
        }
    },
    methods: {
        upload() {
            if (this.file[0]) {
                this.loading = true;

                const formData = new FormData();
                formData.append('file', this.file[0]);

                axios.post('/users/audios', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then((response) => {
                        this.$store.commit('setToast', 'Загружено');
                        this.updateList();
                    })
                    .finally(() => this.loading = false);
            }
        },
        updateList() {
            axios.get('/api/users/' + this.$store.state.user.user.id + '/audios')
                .then(response => {
                    this.audios = response.data.data;
                });
        },
        destroy(id) {
            axios.delete('/users/audios/' + id)
                .then(() => {
                    this.audios = this.audios.filter((audio) => audio.id !== id);
                    this.$store.commit('setToast', 'Удалено');
                });
        },
    },
    mounted() {
        this.updateList();
    },
    components: {AudioListItem, AudioButton, LayoutContent}
};
</script>

<style scoped>

</style>
