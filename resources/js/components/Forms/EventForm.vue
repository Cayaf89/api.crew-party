<template>
    <div class="container-sm">
        <div class="d-flex align-items-center justify-content-center py-4" v-if="loadingEvent">
            <loader></loader>
        </div>
        <template v-else>
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <file-upload
                        input-id="event-logo"
                        accept="image/*"
                        @input-file="inputFile"
                        :min-height="300"
                    >
                        <img :src="event.logo" height="300" class="event-logo" alt="logo">
                    </file-upload>
                    <div class="error" v-for="error in logoError">
                        {{ error }}
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    <h2 class="event-name" v-if="!isNameEdit">
                        {{ event.name }}
                    </h2>
                    <div class="d-flex flex-column" v-else>
                        <div class="input-group">
                            <input type="text" class="input-event-name" v-model="event.name"
                                   placeholder="Nom de l'évènement "
                                   :style="getInputWidthStyle"
                                   autofocus
                                   autocomplete="event-name">
                            <div class="input-group-append">
                                <button type="button" class="input-event-name-button input-group-text"
                                        @click.prevent="() => saveEventField('name')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                        <div class="error" v-for="error in nameError">
                            {{ error }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="event-description pt-4" v-html="event.description" v-if="!isDescriptionEdit"></div>
                    <div v-else>
                        <ckeditor :editor="editor" v-model="event.description" :config="editorConfig"></ckeditor>
                        <div class="error" v-for="error in descriptionError">
                            {{ error }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5" v-if="eventId === null">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <loading-button type="button" class="btn btn-primary" :loading="submitting"
                                    :on_click="submitEvent">
                        Enregistrer
                    </loading-button>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import { imageResize } from "../../services/fileService";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import '@ckeditor/ckeditor5-build-classic/build/translations/fr.js';
import DataTable from "../../../js/components/Tables/DataTable";
import CardDeck from "../../../js/components/Cards/CardDeck";
import { TOOLBAR_ITEMS } from "../../constants/ckeditor_options";

export default {
    name: "EventForm",
    store: Store,
    components: {
        DataTable,
        CardDeck,
    },
    props: {
        eventId: Number,
        crewId: Number,
    },
    data: function () {
        return {
            editor: ClassicEditor,
            editorConfig: {
                language: 'fr',
                toolbar: {
                    items: TOOLBAR_ITEMS
                },
            },
            event: {},
            logo: {},
            isNameEdit: false,
            isDescriptionEdit: false,
            nameError: null,
            descriptionError: null,
            logoError: null,
            submitting: false,
            loadingEvent: false,
        }
    },
    mounted() {
        this.loadEvent();
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
            this.event.logo = URL.createObjectURL(this.logo.file)
            if (this.event?.id) {
                let data = new FormData();
                data.append('logo', this.logo.file);
                axios.post('/api/event/' + this.event.id + '/logo/', data)
                    .then(res => {
                        this.event.logo = res.data.logo;
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
            if (this.$props.crewId) {
                this.submitting = true;
                let data = new FormData();
                data.append('logo', this.logo.file ? this.logo.file : 'null');
                data.append('name', this.event.name);
                data.append('description', this.event.description);
                axios.post('/api/crew/' + this.$props.crewId + '/event', data)
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
            }
        },
        loadEvent: function () {
            if (this.$props.eventId) {
                this.loadingEvent = true;
                axios.get('/api/event/' + this.$props.eventId)
                    .then(res => {
                        this.event = res.data.data;
                        this.loadingEvent = false;
                    })
            }
        }
    },
    computed: {
        getInputWidthStyle: function () {
            return 'width: ' + (this.event.name.length > 0 ? (((this.event.name.length + 1) * 1.1) + 0.625) : 9.84) + 'rem'
        }
    },
}
</script>

<style scoped>

</style>
