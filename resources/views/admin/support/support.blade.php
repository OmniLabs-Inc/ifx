@extends('admin.layouts.master')
@section('title',__('support'))
@section('content')
    <div class="card">
        <div class="card-body p-0">
            <table class="table s7__table">
                <thead>
                    <tr>
                        <th>{{__('Sl')}}</th>
                        <th>{{__('Ticket Id')}}</th>
                        <th>{{__('User Name')}}</th>
                        <th>{{__('Subject')}}</th>
                        <th>{{__('Raised Time')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($all_support as $key=>$data)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$data->ticket}}</td>
                        <td>{{$data->user_member->name}}</td>
                        <td>{{$data->subject}}</td>
                        <td>{{$data->created_at->format('F dS, Y')}}</td>
                        <td>
                        @if($data->status == 1)
                            <span class="badge bg-warning"> {{__('Opened')}}</span>
                        @elseif($data->status == 2)
                            <span class="badge bg-success">  {{__('Answered')}} </span>
                        @elseif($data->status == 3)
                            <span class="badge bg-info"> {{__('User Reply')}} </span>
                        @elseif($data->status == 9)
                            <span class="badge bg-danger">  {{__('Closed')}} </span>
                        @endif
                    </td>
                    <td>
                    <a class="btn s7__btn-primary s7__bg-base" href="{{route('support.admin.reply', $data->ticket )}}">{{__('View')}}</a>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{$all_support->links('pagination::bootstrap-4')}} 
            </div>
        </div>
    </div>
@stop