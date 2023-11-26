@extends('layouts.app')

@section('title', trans('app.page_title') . ' | خانه')

@section('content')

<div class="container my-5">
    <div class="row">
        {{ view('dashboard.item', ['fa_icon' => 'plus-circle'   , 'title' => 'درخواست جدید'    , 'url' => '/application/create']) }}
        {{ view('dashboard.item', ['fa_icon' => 'clipboard-list', 'title' => 'سابقه درخواست ها', 'url' => '/application/list' ]) }}            
    </div>
</div>

@endsection

@section('js-down')
    <script type="text/javascript" src="{{ url('/assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/js/script.js') }}?time={{ time() }}"></script>
    <script type="text/javascript" src="{{ url('/assets/js/home.js') }}?time={{ time() }}"></script>
@show