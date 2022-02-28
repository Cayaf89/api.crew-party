@extends('index')

@push('scripts')
    <script src="{{ mix('js/views/my-parameters/my-parameters.js') }}" defer></script>
@endpush

@push('css')
    <link href="{{ mix('css/views/my-parameters/my-parameters.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div id="my-parameters-page">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex align-items-center justify-content-center flex-column">
                    <file-upload
                            accept="image/*"
                            @input-file="inputFile"
                            :min-height="150"
                            :min-width="150"
                            class="d-flex flex-column"
                    >
                        <img :src="logoSrc" height="150" width="150" class="user-logo" alt="logo">
                        <div>
                            <i class="far fa-image"></i> Modifier l'avata
                        </div>
                    </file-upload>
                </div>
            </div>
            <div class="row d-flex align-items-center justify-content-center mt-5">
                <div class="col-4">
                    <label for="user-firstname">Prénom</label>
                    <input type="text" id="user-firstname" class="form-control" v-model="firstname"
                           placeholder="Prénom">
                </div>
                <div class="col-4">
                    <label for="user-firstname">Nom</label>
                    <input type="text" id="user-lastname" class="form-control" v-model="lastname" placeholder="Nom">
                </div>
            </div>
            <div class="row d-flex align-items-center justify-content-center mt-5">
                <div class="col-4">
                    <label for="user-firstname">Pseudo</label>
                    <input type="text" id="user-username" class="form-control" v-model="username" placeholder="Pseudo">
                </div>
                <div class="col-4">
                    <label for="user-firstname">Email</label>
                    <input type="text" id="user-email" class="form-control" v-model="email" placeholder="Email">
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <loading-button type="button" class="btn btn-primary" :loading="submitting" :on_click="submit">
                        Enregistrer
                    </loading-button>
                </div>
            </div>
        </div>
    </div>
@endsection
