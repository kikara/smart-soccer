<template>
    <layout-content>

        <v-sheet
            class="p-3"
        >
            <div class="text-h6">Настройки фото</div>
            <v-divider></v-divider>

            <v-form
                @submit.prevent="updateAvatar"
            >
                <v-file-input
                    accept="image/png, image/jpeg, image/bmp"
                    placeholder="Выбери фото"
                    prepend-icon="mdi-camera"
                    label="Изменить"
                    v-model="form.file"
                    :loading="form.loading"
                    variant="underlined"
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


    </layout-content>
</template>

<script>
import NavigationDrawer from "../ui/NavigationDrawer.vue";
import LayoutContent from "../ui/LayoutContent.vue";

export default {
    name: "Setting",
    data() {
        return {
            form: {file: null, loading: false}
        }
    },
    methods: {
        updateAvatar(event) {
            if (this.form.file && this.form.file[0]) {
                this.form.loading = true;

                const formData = new FormData();
                formData.append('avatar', this.form.file[0]);

                axios.post('/users/avatar', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then((response) => {
                        this.$store.commit('setToast', 'Изменено');
                    })
                    .finally(() => {
                        this.form.loading = false;
                    })
                ;
            }
        },
    },
    components: {
        LayoutContent,
        NavigationDrawer
    }
}
</script>

<style scoped>

</style>
