@extends('layouts.backend')

@section('title', $server->name)

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')
    @foreach ($notices as $n)
        @include('elements._notification', [
            'level' => 'warning',
            'title' => $n['title'],
            'message' => htmlspecialchars($n['message']),
        ])
    @endforeach

@endsection

@section('content')
    
    @include('admin.sending_servers.form.' . $server->type, ['identities' => $identities, 'bigNotices' => $bigNotices])

@endsection
