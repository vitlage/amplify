@extends('layouts.frontend')

@section('title', $list->name)

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/chart.js') }}"></script>
@endsection

@section('page_header')

            @include("lists._header")

@endsection

@section('content')

    @include("lists._menu")

    <h3 class="text-semibold text-teal-800">{{ trans('messages.Embedded_form') }}</h3>
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-semibold">{{ trans('messages.Form_options') }}</h4>
            <form action="{{ action("MailListController@embeddedForm", $list->uid) }}" class="embedded-options-form custom-box">
                <div class="row">
                    <div class="col-md-3">
                        @include('helpers.form_control', ['type' => 'text',
                                'name' => 'form_title',
                                'label' => trans('messages.form_title'),
                                'value' => trans('messages.Subscribe_to_our_mailing_list'),
                                'help_class' => 'list'
                        ])

                        @include('helpers.form_control', ['type' => 'text',
                                'name' => 'redirect_url',
                                'label' => trans('messages.list.embedded_form.redirect_url'),
                                'value' => '',
                                'help_class' => 'list',
                                'placeholder' => trans('messages.list.redirect_url.placeholder'),
                        ])
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{!! trans('messages.show_only_required_fields', ["link" => action('FieldController@index', $list->uid)]) !!}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'required_fields',
                                    'label' => '',
                                    'value' => 'no',
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ trans('messages.stylesheet_included') }}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'stylesheet',
                                    'label' => '',
                                    'value' => 'yes',
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ trans('messages.include_javascript') }}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'javascript',
                                    'label' => '',
                                    'value' => 'yes',
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('messages.embeded_form.show_invisible') }}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'show_invisible',
                                    'label' => '',
                                    'value' => 'no',
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">

                                @include('helpers.form_control', ['type' => 'textarea',
                                    'name' => 'custom_css',
                                    'class' => 'height-100 text-small',
                                    'label' => trans('messages.custom_css'),
                                    'value' => ".subscribe-embedded-form {\n     color: #333\n}\n.subscribe-embedded-form label {\n     color: #555\n}",
                                    'help_class' => 'list'
                                ])

                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr />
    <div class="embedded-form-result">
        @if (array_key_exists('stylesheet', request()->all()))
            <div class="row">
                <div class="col-md-6 box-style">
                    <h4 class="text-semibold">{{ trans('messages.Copy_paste_onto_your_site') }}</h4>
                        <pre class="language-markup content-group embedded-code"><code></code></pre>
                        <code style="height: 400px" class="form-control main-code hide">@include("lists._embedded_form_content", request()->all())</code>
                </div>
                <div class="col-md-6 box-style">
                    <h4 class="text-semibold">{{ trans('messages.preview') }}</h4>
                    <?php
                        $params = request()->all();
                        $params["uid"] = $list->uid;
                    ?>
                    <iframe class="embedded_form" src="{{ action("MailListController@embeddedFormFrame", $params) }}"></iframe>
                </div>
            </div>
        @endif
    </div>

    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/ui/prism.min.js') }}"></script>
@endsection
