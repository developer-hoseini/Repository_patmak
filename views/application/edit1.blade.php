@extends('layouts.app')

@section('title',  trans('app.page_title') . ' | ایجاد درخواست جدید' )

@section('content')

<div class="mt-5">
    {{ view('application.steps') }}
    <div class="container mb-5">
        <div class="row">
            <div class="col-12 steps">
                @include('application.edit.steps.step-conditions')
                @include('application.edit.steps.step-primary-info')
                @include('application.edit.steps.step-secondary-info')
            </div>
        </div>        
    </div>
</div>

<script type="text/javascript">
    let application_id = '{{ $application_id }}'
</script>

@endsection