<template>
    <div>
        <crew-user-side-bar
            v-if="crewId !== null"
            :crew-id="crewId"
        ></crew-user-side-bar>
        <div class="d-flex align-items-center justify-content-center py-4" v-if="loadingCrew">
            <loader></loader>
        </div>
        <div class="container" v-else>
            <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <div class="d-flex flex-column">
                        <file-upload
                            input-id="crew-logo"
                            accept="image/*"
                            @input-file="inputFile"
                            :min-height="150"
                            :min-width="150"
                        >
                            <img :src="crew.logo" height="150" width="150" class="crew-logo" alt="logo">
                        </file-upload>
                        <div class="error" v-for="error in logoError">
                            {{ error }}
                        </div>
                    </div>
                    <div class="crew-name ml-5" v-if="!isNameEdit">
                        {{ crew.name }}
                    </div>
                    <div class="d-flex flex-column" v-else>
                        <div class="input-group ml-5 w-auto">
                            <input type="text" class="input-crew-name" v-model="crew.name" placeholder="Nom du Crew"
                                   :style="getInputWidthStyle" autofocus autocomplete="crew-name">
                            <div class="input-group-append">
                                <button type="button" class="input-crew-name-button input-group-text"
                                        @click.prevent="() => saveCrewField('name')">
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
            <div class="row mt-4">
                <div class="col-12">
                    <div class="crew-description" v-html="crew.description" v-if="!isDescriptionEdit"></div>
                    <div v-else>
                        <ckeditor :editor="editor" v-model="crew.description" :config="editorConfig"></ckeditor>
                        <div class="error" v-for="error in descriptionError">
                            {{ error }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4" v-if="crew.id !== null">
                <div class="col-12 d-flex justify-content-between">
                    <div class="h3">
                        Évènements
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Recherche" v-model="eventFilter">
                        </div>
                        <button type="button" class="btn btn-primary mx-2" @click="addEvent">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-primary" @click="refreshEvents">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mt-4" v-if="crew.id !== null">
                <div class="col-12">
                    <card-deck
                        :data="events"
                        :get-data="getEvents"
                        :infinite-id="eventsInfiniteId"
                        :on-card-click="onCardClickEvent"
                    ></card-deck>
                </div>
            </div>
            <div class="row mt-5" v-if="crew.id === null">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <loading-button type="button" class="btn btn-primary" :loading="submitting" :on_click="submitCrew">
                        Enregistrer
                    </loading-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CrewUserSideBar from "../SideBars/CrewUserSideBar";
import DataTable from "../Tables/DataTable";
import CardDeck from "../Cards/CardDeck";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { TOOLBAR_ITEMS } from "../../constants/ckeditor_options";
import { imageResizeSquare } from "../../services/fileService";

export default {
    name: "CrewForm",
    components: {
        CrewUserSideBar,
        DataTable,
        CardDeck,
    },
    props: {
        crewId: Number
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
            crew: {},
            users: [],
            events: [],
            logo: {},
            isNameEdit: false,
            isDescriptionEdit: false,
            nameError: null,
            descriptionError: null,
            logoError: null,
            submitting: false,
            eventsInfiniteId: +new Date(),
            eventPage: 1,
            eventFilter: '',
            loadingUsers: false,
            loadingEvents: false,
            loadingCrew: false,
            deletingEvent: false,
        }
    },
    mounted() {
        this.loadCrew();
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
            this.logo.file = await imageResizeSquare(URL.createObjectURL(newFile.file), 400);
            this.crew.logo = URL.createObjectURL(this.logo.file)
            if (this.crew?.id) {
                let data = new FormData();
                data.append('logo', this.logo.file);
                axios.post('/api/crew/' + this.crew.id + '/logo/', data)
                    .then(res => {
                        this.crew.logo = res.data.logo;
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
        getEvents: function ($state) {
            if (this.crew.id) {
                this.source?.cancel && this.source.cancel()
                const CancelToken = axios.CancelToken;
                this.source = CancelToken.source();
                axios.get('/api/crew/' + this.$props.crewId + '/events', {
                    params: {
                        page: this.eventPage,
                        filter: this.eventFilter,
                    },
                    cancelToken: this.source.token
                })
                    .then(res => {
                        if (res.data.data.length) {
                            this.eventPage++;
                            this.events.push(...res.data.data);
                            $state.loaded();
                        }
                        else {
                            $state.complete();
                        }
                    })
            }
        },
        refreshEvents: function () {
            this.eventPage = 1;
            this.events = []
            this.eventsInfiniteId += 1;
        },
        onCardClickEvent: function (eventId) {
            window.location.href = '/event/' + eventId
        },
        addEvent: function () {
            this.$store.commit('setModal', {
                type: 'createUpdateEvent',
                value: {
                    show: true,
                    classic_editor: this.editor,
                    crew_id: this.crew.id,
                    callback: this.refreshEvents
                }
            })
        },
        loadCrew: function () {
            if (this.$props.crewId) {
                this.loadingCrew = true;
                axios.get('/api/crew/' + this.$props.crewId)
                    .then(res => {
                        this.crew = res.data.data;
                        this.loadingCrew = false;
                    })
            }
        }
    },
    computed: {
        getInputWidthStyle: function () {
            return 'width: ' + (this.crew.name.length > 0 ? (((this.crew.name.length + 1) * 0.82) + 0.625) : 9.84) + 'rem'
        }
    },
    watch: {
        eventFilter: function () {
            this.refreshEvents();
        }
    },
}
</script>

<style scoped>

</style>
