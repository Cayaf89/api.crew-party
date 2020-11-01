<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script src="https://kit.fontawesome.com/934c983142.js" crossorigin="anonymous" defer async></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('css')
</head>
<body>
    <div id="app">
        @include('navbar.navbar')

        <main class="py-4">
            @yield('content')
        </main>

        <div id="vue-components">
            <modal-create-update-crew  v-if="$store.state.modal.createUpdateCrew.show"  v-bind="$store.state.modal.createUpdateCrew" :on-close="function() { $store.commit('setModal', { type: 'createUpdateCrew', value: { show: false }}) }"></modal-create-update-crew>
        </div>
    </div>
</body>
</html>
