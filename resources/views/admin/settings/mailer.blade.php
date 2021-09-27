@extends('layouts.backend')

@section('title', trans('messages.settings'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
	
    @if (count($errors) > 0 && $errors->has('smtp_valid'))
        <!-- Form Error List -->
        <div class="alert alert-danger alert-noborder">
            <ul>
                @foreach ($errors->all() as $key => $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="custom-box2">
    
    <form action="{{ action('Admin\SettingController@mailer') }}" method="POST" class="form-validate-jqueryz mailer-form">
        {{ csrf_field() }}
        
        <div class="tabbable">
            @include("admin.settings._tabs")

            <div class="tab-content">
                <p>{{ trans('messages.system_email.intro') }}</p>

                @include("admin.settings._mailer")
            </div>
        </div>
    </form>
</div>    
    <script>
        function toogleMailer() {
            var value = $("select[name='env[MAIL_DRIVER]']").val();
            $('.mailer-setting').hide();
            $('.mailer-setting.' + value).show();
        }
        
        $(document).ready(function() {
            // SMTP toogle
            toogleMailer();
            $("select[name='env[MAIL_DRIVER]']").change(function() {
                toogleMailer();
            });
        });
    </script>
@endsection
