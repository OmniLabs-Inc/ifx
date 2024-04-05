@extends('admin.layouts.master')
@section('title',__('Withdraw Log'))
@section('content')
    <div class="card">
        <div class="card-body p-0">
            <table class="table s7__table">
                <thead>
                <tr>
                    <th> @lang('Wd Id') </th>
                    <th> @lang('User') </th>
                    <th> @lang('Amount') </th>
                    <th> @lang('Charge') </th>
                    <th> @lang('Method') </th>
                    <th> @lang('Requested On')</th>
                    <th> @lang('Processed On')</th>
                    <th> @lang('Status')</th>
                    <th> @lang('Details') </th>
                </tr>

                </thead>
                <tbody>
                @foreach($withdraw as $key=>$data)
                    <tr class="@if($data->status == 3) danger @elseif($data->status == 1) success @else warning @endif">

                        <td >{{$data->withdraw_id}}</td>
                        <td>
                            <p><a href="{{route('user.view', $data->user->id)}}">{{$data->user->name}}</a> </p>
                            <p>{{$data->user->email}}</p>
                        </td>
                        <td>{{$data->amount}} {{$general->currency}}</td>
                        <td>{{$data->charge}} {{$general->currency}}</td>
                        <td>{{$data->method_name}}</td>

                        
                        <td>{{date('g:ia \o\n l jS F Y', strtotime($data->created_at))}}</td>
                        <td>{{date('g:ia \o\n l jS F Y', strtotime($data->updated_at))}}</td>
                        <td>
                            @if($data->status == 3)
                                <span class="badge bg-danger">@lang('REFUNDED')</span>
                            @elseif($data->status == 1)
                                <span class="badge bg-success"> @lang('PAID')</span>
                                @else
                                <span class="badge bg-warning"> @lang('PENDING')</span>
                            @endif
                        </td>
                        <td>
                            <a type="button" class="btn btn-sm btn-info" href="{{route('withdraw.detail.user',$data->id)}}" >@lang('View')</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{$withdraw->links('pagination::bootstrap-4')}} 
            </div>
        </div>
    </div>
@endsection