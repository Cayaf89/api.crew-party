@extends('index')

@push('scripts')
    <script src="{{ mix('js/views/event/show/event-show.js') }}" defer></script>
@endpush

@push('css')
    <link href="{{ mix('css/views/event/show/event-show.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div id="event-show-page">
        <div class="container-sm">
            <div class="mb-3">
                <event-form
                        @if(!empty($event_id))
                        :event-id="{{ $event_id }}"
                        @endif
                        @if(!empty($crew_id))
                        :crew-id="{{ $crew_id }}"
                        @endif
                ></event-form>
            </div>

            <event-choices-form
                    @if(!empty($event_id))
                    :event-id="{{ $event_id }}"
                    @endif
            ></event-choices-form>
        </div>
    </div>
@endsection
