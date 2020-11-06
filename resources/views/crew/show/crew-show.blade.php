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
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <file-upload
                            accept="image/*"
                            @input-file="inputFile"
                            :min-height="150"
                            :min-width="150"
                    >
                        <img :src="logoSrc" height="150" width="150" class="crew-logo" alt="logo">
                    </file-upload>
                    <div class="crew-name ml-5" v-if="!isNameEdit">
                        @{{ crew.name }}
                    </div>
                    <div class="input-group ml-5 w-auto" v-else>
                        <input type="text" class="input-crew-name" v-model="crew.name" placeholder="Nom"
                               :style="'width: ' + ((crew.name.length + 1) * 0.82) + 'rem'">
                        <div class="input-group-append">
                            <button type="button" class="input-crew-name-button input-group-text" @click.prevent="saveCrewName">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="crew-description" v-html="crew.description" v-if="!isDescriptionEdit"></div>
                    <div v-else>
                        <ckeditor :editor="editor" v-model="crew.description" :config="editorConfig"></ckeditor>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
