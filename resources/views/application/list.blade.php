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
                            <th>نوع درخواست</th>
                            <th>سازمان</th>
                            <th>استان</th>
                            <th>نوع پروانه</th>
                            <th>نوع مدرک تحصیلی</th>
                            <th>تاریخ درخواست</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    @if(count($applications))
                    <tbody>
                        @foreach ($applications as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->req_type_title }}</td>
                            <td>{{ $item->morg_title }}</td>
                            <td>{{ $item->province_title }}</td>
                            <td>{{ $item->license_type_title }}</td>
                            <td>{{ $item->study_field_title }}</td>
                            <td class="ltr text-center">{{ $item->created_at }}</td>
                            <td>{{ $item->status_title }}</td>
                            <td>
                            @if($item->status_id == 2)
                                <a href="/application/{{ $item->application_id }}/pay"><button class="btn btn-success">پرداخت</button></a>  
                            @elseif($item->status_id == 3)   
                                <a href="/application/{{ $item->application_id }}/receipt" target="_blank"><button class="btn btn-primary">رسید</button></a>
                            @else
                                -
                            @endif
                            </td>
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