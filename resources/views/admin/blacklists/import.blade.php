@extends('layouts.backend')

@section('title', trans('messages.blacklist.import'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/pickers/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection


@section('content')
    @if (is_object($system_job))
        <div class="sub-section">
            <h3 class="text-semibold mt-0">{{ trans('messages.blacklist.import_process') }}</h3>

            <div class="progress-box" data-url="{{ action('Admin\BlacklistController@importProcess', ['system_job_id' => $system_job->id]) }}">
            </div>

        </div>
    @else
        <div class="sub-section">
            <h3 class="text-semibold mt-0">{{ trans('messages.blacklist.upload_list_from_file') }}</h3>

            <form action="{{ action('Admin\BlacklistController@import') }}" method="POST" class="form-validate-jquery" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-6">
                        <p>{!! trans('messages.blacklist.import_file_help', [
                            'max' => \Acelle\Library\Tool::maxFileUploadInBytes()
                        ]) !!}</p>

                        @include('helpers.form_control', [
                            'required' => true,
                            'type' => 'file',
                            'label' => '',
                            'name' => 'file',
                            'value' => ''
                        ])

                        <div class="text-left">
                            <button class="btn bg-teal mr-10 click-effect"><i class="icon-check"></i> {{ trans('messages.import') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

@endsection
