import { imageResize } from "../../../js/services/fileService";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

const app = new Vue({
    el: '#crew-show-page',
    store: Store,
    data: function () {
        return {
            editor: ClassicEditor,
            editorConfig: {
                // The configuration of the editor.
            },
            crew: window.crew,
            logo: {},
            logoSrc: window.crew?.logo ? window.crew.logo : '/storage/images/png-clipart-computer-file-friends-gathering-love-child.png',
            isNameEdit: false,
            isDescriptionEdit: false,
            nameError: null,
            descriptionError: null,
        }
    },
    mounted() {
        $(document).on('click', event => {
            if (!this.isNameEdit && $(event.target).hasClass('crew-name')) {
                this.isNameEdit = true;
            }
            else if (this.isNameEdit && !$(event.target).hasClass('input-crew-name') && !$(event.target).hasClass('input-crew-name-button') && $(event.target).parents('.input-crew-name-button').length === 0) {
                this.saveCrewName();
                this.isNameEdit = false;
            }

            if (!this.isDescriptionEdit && ($(event.target).hasClass('crew-description') || $(event.target).parents('.crew-description').length > 0)) {
                this.isDescriptionEdit = true;
            }
            else if (this.isDescriptionEdit && $(event.target).parents('.ck-editor').length === 0) {
                this.saveCrewDescription();
                this.isDescriptionEdit = false;
            }
        })
    },
    methods: {
        inputFile: async function (newFile, oldFile) {
            this.logo = newFile;
            this.logo.file = await imageResize(URL.createObjectURL(newFile.file), 400, 400);
            this.logoSrc = URL.createObjectURL(newFile.file)
            if (this.crew?.id) {
                let data = new FormData();
                data.append('logo', newFile.file);
                axios.post('/api/crew/logo/' + this.crew.id, data)
                    .then(res => {
                        this.logoSrc = res.data.logo;
                        this.$store.commit('updateSideBarCrews')
                    })
                    .catch(error => {
                        if (error?.response?.data) {
                            toastr.error(error.response.data);
                        }
                    })
            }
        },
        saveCrewName: function () {
            axios.post('/api/crew/' + this.crew.id, {
                name: this.crew.name,
            })
                .then(res => {
                    this.errors = {};
                    this.isNameEdit = false;
                    toastr.success('Le nom du Crew a bien été enregistré')
                })
                .catch(error => {
                    if (error?.response?.data?.errors) {
                        this.nameError = error.response.data.errors?.name;
                    }
                })
        },
        saveCrewDescription: function () {
            axios.post('/api/crew/' + this.crew.id, {
                description: this.crew.description,
            })
                .then(res => {
                    this.errors = {};
                    toastr.success('Le nom du Crew a bien été enregistré')
                })
                .catch(error => {
                    if (error?.response?.data?.errors) {
                        this.descriptionError = error.response.data.errors?.description;
                    }
                })
        }
    }
})
