@extends('index')

@push('scripts')
    <script src="{{ mix('js/views/event/list/event-list.js') }}" defer></script>
@endpush

@push('css')
    <link href="{{ mix('css/views/event/list/event-list.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div id="event-list-page">
        <div class="container">
            <div class="row mb-5">
                <div class="col-6">
                    <div class="h3">
                        Liste des évènements
                    </div>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    <a class="btn btn-primary" href="{{ route('event-create') }}">
                        Créer un évènement
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">

                </div>
            </div>
        </div>
    </div>
@endsection
