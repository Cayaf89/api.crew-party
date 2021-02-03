@extends('index')

@push('scripts')
    <script>
        window.event_object = {!! json_encode($event ?? []) !!}
    </script>
    <script src="{{ mix('js/views/event/show/event-show.js') }}" defer></script>
@endpush

@push('css')
    <link href="{{ mix('css/views/event/show/event-show.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div id="event-show-page">
        <div class="container-sm">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <file-upload
                            input-id="event-logo"
                            accept="image/*"
                            @input-file="inputFile"
                            :min-height="300"
                    >
                        <img :src="logoSrc" height="300" class="event-logo" alt="logo">
                    </file-upload>
                    <div class="error" v-for="error in logoError">
                        @{{ error }}
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    <h2 class="event-name" v-if="!isNameEdit">
                        @{{ event.name }}
                    </h2>
                    <div class="d-flex flex-column" v-else>
                        <div class="input-group">
                            <input type="text" class="input-event-name" v-model="event.name" placeholder="Nom du Crew"
                                   :style="getInputWidthStyle"
                                   autofocus
                                   autocomplete="event-name">
                            <div class="input-group-append">
                                <button type="button" class="input-event-name-button input-group-text"
                                        @click.prevent="saveCrewName">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                        <div class="error" v-for="error in nameError">
                            @{{ error }}
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
                            @{{ error }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4" v-if="event.id !== null">
                <div class="col-12">
                </div>
            </div>
            <div class="row mt-5" v-if="event.id === null">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <loading-button type="button" class="btn btn-primary" :loading="submitting"
                                    :on_click="submitEvent">
                        Enregistrer
                    </loading-button>
                </div>
            </div>
        </div>
    </div>
@endsection
