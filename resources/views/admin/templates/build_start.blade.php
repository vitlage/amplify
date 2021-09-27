@extends('layouts.backend')

@section('title', trans('messages.create_template'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('js/tinymce/tinymce.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection



@section('content')
    
                <div class="row">
					@foreach(Acelle\Model\Template::templateStyles() as $name => $style)
						<div class="col-xxs-12 col-xs-6 col-sm-3 col-md-2">
							<a href="{{ action('Admin\TemplateController@build', ['style' => $name]) }}">
								<div class="panel panel-flat panel-template-style">
									<div class="panel-body">
										<img src="{{ url('images/template_styles/'.$name.'.png') }}" />
										<h5 class="mb-0 text-center">{{ trans('messages.'.$name) }}</h5>
									</div>
								</div>
							</a>
						</div>
					@endforeach
                </div>
@endsection
