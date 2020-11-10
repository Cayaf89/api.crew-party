import { imageResize } from "../../../js/services/fileService";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import '@ckeditor/ckeditor5-build-classic/build/translations/fr.js';
import CrewUserTable from "../../../js/components/Tables/CrewUserTable";
import CrewUserSideBar from "../../../js/components/SideBars/CrewUserSideBar";

const app = new Vue({
    el: '#crew-show-page',
    store: Store,
    components: {
        CrewUserTable,
        CrewUserSideBar,
    },
    data: function () {
        return {
            editor: ClassicEditor,
            editorConfig: {
                language: 'fr',
                toolbar: {
                    items: [
                        "heading",
                        "|",
                        "bold",
                        "italic",
                        "link",
                        "bulletedList",
                        "numberedList",
                        "|",
                        "indent",
                        "outdent",
                        "|",
                        "blockQuote",
                        "insertTable",
                        "undo",
                        "redo"
                    ]
                },
            },
            crew: {
                id: window.crew?.id ? window.crew?.id : null,
                name: window.crew?.name ? window.crew?.name : 'Nouveau Crew',
                description: window.crew?.description ? window.crew?.description : 'Description du nouveau Crew',
                owner_id: window.crew?.owner_id ? window.crew?.owner_id : this.$store.state.user.id,
            },
            users: [],
            logo: {},
            logoSrc: window.crew?.logo ? window.crew.logo : '/storage/images/default-logo.png',
            isNameEdit: false,
            isDescriptionEdit: false,
            nameError: null,
            descriptionError: null,
            logoError: null,
            submitting: false,
            loadingUsers: false,
        }
    },
    mounted() {
        $(document).on('click', event => {
            if (!this.isNameEdit && $(event.target).hasClass('crew-name')) {
                this.isNameEdit = true;
            }
            else if (this.isNameEdit && !$(event.target).hasClass('input-crew-name') && !$(event.target).hasClass('input-crew-name-button') && $(event.target).parents('.input-crew-name-button').length === 0) {
                this.saveCrewField('name');
            }

            if (!this.isDescriptionEdit && ($(event.target).hasClass('crew-description') || $(event.target).parents('.crew-description').length > 0)) {
                this.isDescriptionEdit = true;
            }
            else if (this.isDescriptionEdit && $(event.target).parents('.ck-editor').length === 0) {
                this.saveCrewField('description');
            }
        })
    },
    methods: {
        inputFile: async function (newFile, oldFile) {
            this.logo = newFile;
            this.logo.file = await imageResize(URL.createObjectURL(newFile.file), 400, 400);
            this.logoSrc = URL.createObjectURL(this.logo.file)
            if (this.crew?.id) {
                let data = new FormData();
                data.append('logo', this.logo.file);
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
        saveCrewField: function (field) {
            const fieldCapitalize = field.charAt(0).toUpperCase() + field.slice(1)
            if (this.crew.id) {
                axios.post('/api/crew/' + this.crew.id, {
                    [field]: this.crew[field],
                })
                    .then(res => {
                        this.errors = {};
                        this['is' + fieldCapitalize + 'Edit'] = false;
                        toastr.success('Le champ a bien été enregistré')
                    })
                    .catch(error => {
                        if (error?.response?.data?.errors) {
                            this[field + 'Error'] = error.response.data.errors[field];
                        }
                    })
            }
            else {
                this['is' + fieldCapitalize + 'Edit'] = false;
            }
        },
        submitCrew: function () {
            this.submitting = true;
            let data = new FormData();
            data.append('logo', this.logo.file ? this.logo.file : 'null');
            data.append('name', this.crew.name);
            data.append('description', this.crew.description);
            axios.post('/api/crew/', data)
                .then(res => {
                    this.submitting = false;
                    this.errors = {};
                    toastr.success('Le Crew a bien été créé')
                    location.href = '/crews'
                })
                .catch(error => {
                    if (error?.response?.data?.errors) {
                        this.logoError = error.response.data.errors?.logo;
                        this.nameError = error.response.data.errors?.name;
                        this.descriptionError = error.response.data.errors?.description;
                    }
                    this.submitting = false;
                })
        },
        getUsers: function (page, filter) {
            if (this.crew.id) {
                this.loadingUsers = true;
                axios.get('/api/crew/' + this.crew.id + '/users',{
                    params: {
                        page: page,
                        filter: filter,
                    }
                })
                    .then(res => {
                        this.users = res.data.data;
                        this.totalPage = res.data.meta.last_page;
                    })
                    .finally(() => {
                        this.loadingUsers = false;
                    })
            }
        }
    }
})
