<template>
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer un Crew</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-center flex-column">
                                <file-upload
                                    accept="image/*"
                                    @input-file="inputFile"
                                    :min-height="150"
                                    :min-width="150"
                                >
                                    <img :src="logoSrc" height="150" width="150" class="crew-logo" alt="logo">
                                </file-upload>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-3 col-6">
                                <label for="crew-name">Nom</label>
                                <input id="crew-name" type="text" class="form-control" v-model="name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>Description</label>
                                <ckeditor :editor="editor" v-model="description" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
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
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { imageResize } from "../../services/fileService";

export default {
    name: "ModalCreateUpdateCrew",
    components: {
        ClassicEditor,
    },
    props: {
        onClose: Function,
        crew_id: Number,
        callback: Function
    },
    data() {
        return {
            editor: ClassicEditor,
            editorConfig: {
                // The configuration of the editor.
            },
            logo: null,
            logoSrc: '/storage/images/png-clipart-computer-file-friends-gathering-love-child.png',
            name: '',
            description: '<p>Content of the editor.</p>',
            errors: {},
            submitting: false
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

        if (this.$props.crew_id) {
            axios.get('/api/crew/' + this.$props.crew_id)
                .then(res => {
                    this.logoSrc = res.data.data.logo;
                    this.name = res.data.data.name;
                    this.description = res.data.data.description;
                })
        }
    },
    methods: {
        inputFile: async function (newFile, oldFile) {
            this.logo = newFile;
            this.logo.file = await imageResize(URL.createObjectURL(newFile.file), 400, 400);
            this.logoSrc = URL.createObjectURL(newFile.file)
            if (this.$props.crew_id) {
                let data = new FormData();
                data.append('logo', newFile.file);
                axios.post('/api/crew/logo/' + this.$props.crew_id, data)
                    .then(res => {
                        this.logoSrc = res.data.logo;
                    })
                    .catch(error => {
                        toastr.error(error.response.data);
                    })
            }
        },
        submit: function () {
            if (this.$props.crew_id) {
                axios.post('/api/crew/' + this.$props.crew_id, {
                    name: this.name,
                    description: this.description,
                })
                    .then(res => {
                        this.errors = {};
                        $(this.$el).modal('hide');
                        toastr.success('Crew enregistré')
                    })
                    .catch(error => {
                        if (error?.response?.data?.errors) {
                            this.errors = error.response.data.errors;
                        }
                    })
            }
            else {
                let data = new FormData();
                data.append('logo', this.logo.file);
                data.append('name', this.name);
                data.append('description', this.description);
                axios.post('/api/crew/', data)
                    .then(res => {
                        this.errors = {};
                        $(this.$el).modal('hide');
                        toastr.success('Crew enregistré')
                    })
                    .catch(error => {
                        if (error?.response?.data?.errors) {
                            this.errors = error.response.data.errors;
                        }
                    })
            }
        }
    }
}
</script>

<style scoped>
.crew-logo {
    border-radius: 50%;
    height: 150px;
    width: 150px;
}
</style>
