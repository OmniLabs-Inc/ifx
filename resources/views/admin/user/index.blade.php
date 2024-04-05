@extends('admin.layouts.master')
@section('title',__('User Management'))
@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <div class="card-title uppercase bold"><i class="fa fa-search"></i> @lang('Search Users')</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="form-horizontal" method="GET" action="{{route('username.search')}}">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" required placeholder="@lang('Search By Name')" aria-describedby="basic-addon2">
                        <button class="btn btn-outline-primary" type="submit">@lang('Search')</button>
                    </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <form class="form-horizontal" method="GET" action="{{route('email.search')}}">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email"  placeholder="@lang('Search By Email')" aria-describedby="basic-addon1">
                            <button class="btn btn-outline-primary" type="submit">@lang('Search')</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa fa-user"></i> @lang('User List')</div>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm s7__table">
                <thead>
                <tr>
                    <th> @lang('Sl')</th>
                    <th> @lang('Name') </th>
                    <th>@lang('Email')</th>
                    <th>@lang('Unique ID')</th>
                    <th> @lang('Wallet Balance')</th>
                    <th> @lang('Action') </th>
                </tr>
                </thead>
                <tbody>
                @foreach($user as $key => $data)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$data->name}}</td>
                        <td><b>{{$data->email}}</b></td>
                        <td>{{ $data->user_unique_id}}</td>
                        <td>{{$general->currency}} {{ round($data->balance,8) }}</td>
                        <td>
                            <a class="btn s7__btn-primary s7__bg-base btn-sm" href="{{route('user.view', $data->id)}}"><i class="fa fa-eye"></i>  @lang('View')</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{$user->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
@endsection

