<template>
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer un Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-cover-header">
                    <file-upload
                        input-id="event-cover"
                        accept="image/*"
                        @input-file="inputCover"
                        :min-height="150"
                    >
                        <img :src="coverSrc" height="300" class="event-cover" alt="event cover">
                    </file-upload>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="offset-3 col-6">
                                <label for="crew-name">Nom</label>
                                <input id="crew-name" type="text" class="form-control" v-model="name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>Description</label>
                                <ckeditor :editor="classic_editor" v-model="description"
                                          :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <loading-button type="button" class="btn btn-danger" v-if="$props.event_id" :loading="deletting"
                                    :on_click="deleteEvent">
                        Supprimer
                    </loading-button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Fermer
                    </button>
                    <loading-button type="button" class="btn btn-primary" :loading="submitting" :on_click="submit">
                        Enregistrer
                    </loading-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { imageResize } from "../../services/fileService";

export default {
    name: "ModalCreateUpdateEvent",
    props: {
        onClose: Function,
        event_id: Number,
        crew_id: Number,
        classic_editor: Function,
        callback: Function,
    },
    data() {
        return {
            editorConfig: {},
            cover: null,
            coverSrc: '/storage/event/default-cover.jpg',
            name: '',
            description: '<p>Content of the editor.</p>',
            errors: {},
            submitting: false,
            deletting: false,
        };
    },
    mounted() {
        $(this.$el).modal();
        $(this.$el).on('hidden.bs.modal', () => {
            if (this.$props.callback) {
                this.$props.callback();
            }
            this.$props.onClose();
        });

        if (this.$props.event_id) {
            axios.get('/api/event/' + this.$props.event_id)
                .then(res => {
                    this.coverSrc = res.data.data.cover;
                    this.name = res.data.data.name;
                    this.description = res.data.data.description;
                })
        }
    },
    methods: {
        inputCover: async function (newFile, oldFile) {
            this.cover = newFile;
            this.cover.file = await imageResize(URL.createObjectURL(newFile.file), 2400, 900);
            this.coverSrc = URL.createObjectURL(newFile.file)
            if (this.$props.event_id) {
                let data = new FormData();
                data.append('cover', newFile.file);
                axios.post('/api/event/' + this.$props.event_id + '/cover/', data)
                    .then(res => {
                        this.coverSrc = res.data.cover;
                    })
                    .catch(error => {
                        toastr.error(error.response.data);
                    })
            }
        },
        submit: function () {
            if (this.$props.event_id) {
                axios.post('/api/event/' + this.$props.event_id, {
                    name: this.name,
                    description: this.description,
                })
                    .then(res => {
                        this.errors = {};
                        $(this.$el).modal('hide');
                        toastr.success('Event enregistré')
                    })
                    .catch(error => {
                        if (error?.response?.data?.errors) {
                            this.errors = error.response.data.errors;
                        }
                    })
            }
            else {
                let data = new FormData();
                data.append('cover', this.cover?.file ? this.cover.file : '');
                data.append('name', this.name);
                data.append('description', this.description);
                axios.post('/api/crew/' + this.$props.crew_id + '/event/', data)
                    .then(res => {
                        this.errors = {};
                        $(this.$el).modal('hide');
                        toastr.success('Event enregistré')
                    })
                    .catch(error => {
                        if (error?.response?.data?.errors) {
                            this.errors = error.response.data.errors;
                        }
                    })
            }
        },
        deleteEvent: function () {
            if (this.$props.event_id) {
                axios.delete('/api/event/' + this.$props.event_id)
                    .then(res => {
                        $(this.$el).modal('hide');
                        toastr.success('Event supprimé')
                    })
                    .catch(error => {
                        toastr.error('Une erreur est survenue')
                    })
            }
        }
    }
}
</script>

<style scoped>
.event-cover {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.modal-cover-header {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
