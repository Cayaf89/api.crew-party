@extends('index')

@push('scripts')
    <script src="{{ mix('js/views/crew/show/crew-show.js') }}" defer></script>
@endpush

@push('css')
    <link href="{{ mix('css/views/crew/show/crew-show.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div id="crew-show-page">
        <crew-form :crew-id="{{ $crew_id }}"></crew-form>
    </div>
@endsection
