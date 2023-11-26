@extends('layouts.app')

@section('title',  trans('app.page_title') . ' | لیست درخواست ها' )

@section('content')

<div class="mt-5">
    <div class="container-fluid">
        <div class="row my-5">
            <div class="col-12">
                <table class="table table-striped cell-border table-bordered w-100" >
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>نوع شخص</th>
                            <th>نوع درخواست</th>
                            <th>هزینه (ریال)</th>

                        </tr>
                    </thead>
                    @if(count($prices))
                    <tbody>
                        @foreach ($prices as $price)
                        <tr>
                            <td>{{ $price->cost_id }}</td>
                            <td>{{ $price->person_type_title }}</td>
                            <td>{{ $price->req_type_title }}</td>
                            <td>{{ numEnToFa(number_format($price->amount)) }}</td>                            
                        </tr>
                        @endforeach                        
                    </tbody>
                    @else
                    <tbody>
                        <tr>
                            <td class="text-center" colspan="8">هیچ رکوردی موجود نیست</td>
                        </tr>
                    </tbody>
                    @endif
                </table>                
            </div>
        </div>        
    </div>
</div>    

@endsection