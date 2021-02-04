import { imageResize } from "../../../js/services/fileService";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import '@ckeditor/ckeditor5-build-classic/build/translations/fr.js';
import DataTable from "../../../js/components/Tables/DataTable";
import CardDeck from "../../../js/components/Cards/CardDeck";

const app = new Vue({
    el: '#event-show-page',
    store: Store,
    components: {
        DataTable,
        CardDeck,
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
            event: {
                id: window.event_object?.id ? window.event_object?.id : null,
                name: window.event_object?.name ? window.event_object?.name : 'Nouveau Crew',
                description: window.event_object?.description ? window.event_object?.description : 'Description du nouveau Crew',
                owner_id: window.event_object?.owner_id ? window.event_object?.owner_id : this.$store.state.user.id,
            },
            users: [],
            logo: {},
            logoSrc: window.event_object?.logo ? window.event_object.logo : '/storage/images/default-logo.png',
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
            if (!this.isNameEdit && $(event.target).hasClass('event-name')) {
                this.isNameEdit = true;
            }
            else if (this.isNameEdit && !$(event.target).hasClass('input-event-name') && !$(event.target).hasClass('input-event-name-button') && $(event.target).parents('.input-event-name-button').length === 0) {
                this.saveEventField('name');
            }

            if (!this.isDescriptionEdit && ($(event.target).hasClass('event-description') || $(event.target).parents('.event-description').length > 0)) {
                this.isDescriptionEdit = true;
            }
            else if (this.isDescriptionEdit && $(event.target).parents('.ck-editor').length === 0) {
                this.saveEventField('description');
            }
        })
    },
    methods: {
        inputFile: async function (newFile, oldFile) {
            this.logo = newFile;
            this.logo.file = await imageResize(URL.createObjectURL(newFile.file), 1200, 300);
            this.logoSrc = URL.createObjectURL(this.logo.file)
            if (this.event?.id) {
                let data = new FormData();
                data.append('logo', this.logo.file);
                axios.post('/api/event/' + this.event.id + '/logo/', data)
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
        saveEventField: function (field) {
            const fieldCapitalize = field.charAt(0).toUpperCase() + field.slice(1)
            if (this.event.id) {
                axios.post('/api/event/' + this.event.id, {
                    [field]: this.event[field],
                })
                    .then(res => {
                        this.errors = {};
                        this['is' + fieldCapitalize + 'Edit'] = false;
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
        submitEvent: function () {
            this.submitting = true;
            let data = new FormData();
            data.append('logo', this.logo.file ? this.logo.file : 'null');
            data.append('name', this.event.name);
            data.append('description', this.event.description);
            axios.post('/api/event/', data)
                .then(res => {
                    this.submitting = false;
                    this.errors = {};
                    toastr.success("L'évènement a bien été créé")
                    location.href = '/events'
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
            if (this.event.id) {
                this.loadingUsers = true;
                axios.get('/api/event/' + this.event.id + '/users', {
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
        },
    },
    computed: {
        getInputWidthStyle: function () {
            return 'width: ' + (this.event.name.length > 0 ? (((this.event.name.length + 1) * 1.1) + 0.625) : 9.84) + 'rem'
        }
    },
    watch: {
        eventFilter: function () {
            this.refreshEvents();
        }
    },
})
