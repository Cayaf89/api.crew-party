@extends('index')

@push('scripts')
    <script src="{{ mix('js/views/crew/list/crew-list.js') }}" defer></script>
@endpush

@push('css')
    <link href="{{ mix('css/views/crew/list/crew-list.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div id="crew-list-page">
        <div class="container">
            <div class="row mb-5">
                <div class="col-6">
                    <div class="h3">
                        Liste des Crews
                    </div>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    <a class="btn btn-primary" href="{{ route('crew-create') }}">
                        Créer un Crew
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <crew-table
                            :crews="crews"
                            :get-crews="getCrews"
                            :loading="loadingCrews"
                    ></crew-table>
                </div>
            </div>
        </div>
    </div>
@endsection
