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
                    <div class="row">
                        <div class="col-12">
                            <label for="crew-name">Nom</label>
                            <input id="crew-name" type="text" class="form-control" v-model="name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="crew-name">Description</label>
                            <ckeditor :editor="editor" v-model="description" :config="editorConfig"></ckeditor>
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

export default {
    name: "ModalCreateUpdateCrew",
    components: {
        ClassicEditor
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
                    this.name = res.data.data.name;
                    this.description = res.data.data.description;
                })
        }
    },
    methods: {
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
                axios.post('/api/crew/', {
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
        }
    }
}
</script>

<style scoped>

</style>
