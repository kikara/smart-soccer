<template>
    <layout-content>
        <v-sheet
            class="p-3"
        >

            <div class="text-h6">
                Добавить звук на событие
            </div>

            <v-divider></v-divider>

            <v-form
                @submit.prevent="upload"
                ref="form"
            >
                <div class="row">
                    <div class="col-md">
                        <v-select
                            label="Событие"
                            v-model="form.event"
                            item-title="name"
                            item-value="id"
                            :items="events"
                            variant="underlined"
                            :rules="rules"
                        >
                        </v-select>
                    </div>

                    <div class="col-md">
                        <v-file-input
                            placeholder="Выбери файл"
                            prepend-icon="mdi-music-note"
                            label="Добавить"
                            variant="underlined"
                            accept="audio/mpeg, audio/wav"
                            :rules="fileRules"
                            :loading="loading"
                            v-model="form.file"
                        ></v-file-input>
                    </div>

                </div>

                <div class="row"
                     v-for="parameter in parameters"
                >
                    <div class="col-md">
                        <v-select
                            label="Параметр"
                            item-title="description"
                            item-value="code"
                            variant="underlined"
                            :items="eventParams"
                            v-model="form.eventParameters[parameter - 1].parameter"
                            :rules="rules"
                        >
                        </v-select>
                    </div>
                    <div class="col-md-2">
                        <v-text-field
                            label="Значение"
                            variant="underlined"
                            v-model="form.eventParameters[parameter - 1].value"
                            :rules="rules"
                        ></v-text-field>
                    </div>
                    <div class="col-md-1"
                         v-if="parameter > 1"
                    >
                        <v-btn
                            icon="mdi-close"
                            elevation="0"
                            @click="parameters--"
                        >

                        </v-btn>
                    </div>
                </div>

                <v-btn
                    type="submit"
                    rounded="0"
                    prepend-icon="mdi-upload"
                    elevation="0"
                >
                    Загрузить
                </v-btn>

                <v-btn
                    class="ms-2"
                    @click="parameters++"
                    elevation="0"
                    rounded="0"
                >
                    Добавить параметр
                </v-btn>
            </v-form>

        </v-sheet>

        <v-sheet
            class="mt-2 px-3"
        >
            <v-list>
                <audio-list-item
                    v-for="item in audioList"
                    :item="item"
                    :key="item.id"
                    @destroy="destroy(item.id)"
                >
                    <div class="mt-2 d-flex gap-2">
                        <v-chip
                            v-for="parameter in item.parameters"
                            color="primary"
                        >
                            {{ parameter.name }} - {{ parameter.value }}
                        </v-chip>
                    </div>
                </audio-list-item>
            </v-list>
        </v-sheet>

    </layout-content>
</template>

<script>
import LayoutContent from "../ui/LayoutContent.vue";
import AudioListItem from "../ui/AudioListItem.vue";

export default {
    name: "EventSounds",
    data() {
        return {
            parameters: 1,
            events: [],
            eventParams: [],
            form: {
                event: null,
                eventParameters: [
                    {parameter: '', value: 1}
                ],
                file: null
            },
            loading: false,
            rules: [
                value => {
                    if (value) {
                        return true;
                    }
                    return 'Поле не заполнено!';
                }
            ],
            fileRules: [
                value => {
                    return !value || !value.length || value[0].size < 2000000 || 'Размер файла превышает 2Мб!'
                }
            ],
            audioList: []
        }
    },
    mounted() {
        axios.get('/api/events')
            .then(response => {
                this.events = response.data.data;
            });

        axios.get('/api/events/params')
            .then(response => {
                this.eventParams = response.data.data;
            });

        this.updateList();
    },
    methods: {
        async upload() {
            const {valid} = await this.$refs.form.validate();

            if (valid) {
                const formData = new FormData();

                formData.append('file', this.form.file[0]);
                formData.append('event', this.form.event);

                for (const [index, parameter] of this.form.eventParameters.entries()) {
                    for (const [key, value] of Object.entries(parameter)) {
                        formData.append(`parameters[${index}][${key}]`, value);
                    }
                }

                axios.post('/users/events/audios', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then(response => {
                        this.$store.commit('setToast', 'Загружено');
                        this.updateList();
                    });

            }
        },
        updateList() {
            axios.get('/api/users/' + this.$store.state.user.user.id + '/events/audios')
                .then(response => {
                    this.audioList = response.data.data;
                });
        },
        destroy(id) {
            console.log('id');
            axios.delete('/users/events/audios/' + id)
                .then(response => {
                    this.$store.commit('setToast', 'Удалено');
                    this.updateList();
                });
        },
    },
    watch: {
        parameters: function (newValue, oldValue) {
            if (newValue > oldValue) {
                this.form.eventParameters.push({parameter: '', value: 1});
            } else {
                this.form.eventParameters.splice(oldValue - 1, 1);
            }
        },
    },
    components: {AudioListItem, LayoutContent},
}
</script>

<style scoped>

</style>
