@extends('layouts.backend')

@section('title', trans('messages.settings'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection



@section('content')
<div class="custom-box2">
    <form action="{{ action('Admin\SettingController@general') }}" method="POST" class="form-validate-jqueryz" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="tabbable">
            @include("admin.settings._tabs")
            <div class="tab-content mt-4">
                @include("admin.settings._general")                        
            </div>
        </div>
    </form>
</div>

    <script>
        function changeSelectColor() {
            $('.select2 .select2-selection__rendered, .select2-results__option').each(function() {
                var text = $(this).html();
                if (text == '{{ trans('messages.default') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-teal-600'></i>");
                    }
                }
                if (text == '{{ trans('messages.blue') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-blue'></i>");
                    }
                }
                if (text == '{{ trans('messages.green') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-green'></i>");
                    }
                }
                if (text == '{{ trans('messages.brown') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-brown'></i>");
                    }
                }
                if (text == '{{ trans('messages.pink') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-pink'></i>");
                    }
                }
                if (text == '{{ trans('messages.grey') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-grey'></i>");
                    }
                }
                if (text == '{{ trans('messages.white') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-white'></i>");
                    }
                }
            });
        }

        $(document).ready(function() {
            setInterval("changeSelectColor()", 100);
        });
    </script>
@endsection
