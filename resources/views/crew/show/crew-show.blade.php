@extends('index')

@push('scripts')
    <script>
        window.crew = {!! json_encode($crew ?? []) !!}
    </script>
    <script src="{{ mix('js/views/crew/show/crew-show.js') }}" defer></script>
@endpush

@push('css')
    <link href="{{ mix('css/views/crew/show/crew-show.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div id="crew-show-page">
        <crew-user-side-bar
                v-if="crew.id !== null"
                :crew-id="crew.id"
        ></crew-user-side-bar>
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <div class="d-flex flex-column">
                        <file-upload
                                accept="image/*"
                                @input-file="inputFile"
                                :min-height="150"
                                :min-width="150"
                        >
                            <img :src="logoSrc" height="150" width="150" class="crew-logo" alt="logo">
                        </file-upload>
                        <div class="error" v-for="error in logoError">
                            @{{ error }}
                        </div>
                    </div>
                    <div class="crew-name ml-5" v-if="!isNameEdit">
                        @{{ crew.name }}
                    </div>
                    <div class="d-flex flex-column" v-else>
                        <div class="input-group ml-5 w-auto">
                            <input type="text" class="input-crew-name" v-model="crew.name" placeholder="Nom du Crew"
                                   :style="'width: ' + (crew.name.length > 0 ? ((crew.name.length + 1) * 0.82) : 9.84) + 'rem'"
                                   autofocus
                                   autocomplete="crew-name">
                            <div class="input-group-append">
                                <button type="button" class="input-crew-name-button input-group-text"
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
            <div class="row mt-4">
                <div class="col-12">
                    <div class="crew-description" v-html="crew.description" v-if="!isDescriptionEdit"></div>
                    <div v-else>
                        <ckeditor :editor="editor" v-model="crew.description" :config="editorConfig"></ckeditor>
                        <div class="error" v-for="error in descriptionError">
                            @{{ error }}
                        </div>
                    </div>
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
@endsection
