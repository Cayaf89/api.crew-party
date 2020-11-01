@extends('index')

@push('scripts')
    <script src="{{ mix('js/views/dashboard/dashboard.js') }}" defer></script>
@endpush

@section('content')
    <div id="dashboard-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Dashboard') }}</div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" @click="openModalCreateUpdateCrew">
                                        Create Crew
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <crew-table></crew-table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
