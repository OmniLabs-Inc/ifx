@extends('admin.layouts.master')
@section('title',__('Notifications'))
@section('content')
<div class="card">
    <div class="card-body">
    <p class="card-title text-end"><a href="{{ route('admin.notifications.readAll') }}" class="btn-sm btn btn-primary">@lang('Mark all as read')</a></p>
        <ul class="list-group icon-data-list">
            @foreach($notifications as $data)
                <a class="notify_item @if($data->read_status == 0) unread-notification @endif" href="{{ route('admin.notification.read',$data->id) }}">
                    <li class="notify_content">
                        <div class="d-flex">
                        <img src="{{asset('public/images/user/'.$data->user->image)}}" alt="user">
                            <div>
                                <p class="text-info mb-1">{{$data->user->name}}</p>
                                <p class="mb-0">{{ __($data->title) }}</p>
                                <small>{{ $data->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </li>
                </a>
            @endforeach
        </ul>
        <div class="py-4 justify-content-center pagination flex-wrap pagination-rounded-flat pagination-success">
            {{$notifications->links('pagination::bootstrap-4')}} 
        </div>
    
    </div>
</div>
@endsection