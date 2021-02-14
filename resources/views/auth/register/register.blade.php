@extends('index')

@push('scripts')
    <script src="{{ mix('js/views/auth/register/register.js') }}" defer></script>
@endpush

@section('content')
    <div id="register-page" class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">

                        <div class="form-group row">
                            <label for="name" class="col-md-5 col-form-label text-md-right">
                                Nom d'utilisateur
                            </label>

                            <div class="col-md-6">
                                <input type="text" :class="{'form-control': true, 'is-invalid': errors.username}"
                                       v-model="username" required autocomplete="username" autofocus>

                                <div class="invalid-feedback" role="alert" v-if="errors.username">
                                    <strong class="d-flex" v-for="error in errors.username">
                                        @{{ error }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-5 col-form-label text-md-right">
                                Adresse e-mail
                            </label>

                            <div class="col-md-6">
                                <input type="email" :class="{'form-control': true, 'is-invalid': errors.email}"
                                       v-model="email" required autocomplete="email">

                                <div class="invalid-feedback" role="alert" v-if="errors.email">
                                    <strong class="d-flex" v-for="error in errors.email">
                                        @{{ error }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-5 col-form-label text-md-right">
                                Mot de passe
                            </label>

                            <div class="col-md-6">
                                <input type="password" :class="{'form-control': true, 'is-invalid': errors.password}"
                                       v-model="password" required autocomplete="new-password">

                                <div class="invalid-feedback" role="alert" v-if="errors.password">
                                    <strong class="d-flex" v-for="error in errors.password">
                                        @{{ error }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-5 col-form-label text-md-right">
                                Confirmez le mot de passe
                            </label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" v-model="passwordConfirm" required
                                       autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-primary" @click="submit">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
