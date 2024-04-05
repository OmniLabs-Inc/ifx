@extends('admin.layouts.master')
@section('title',__('Transaction'))
@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <h4>@lang('Search from log')</h4>
        </div>
        <div class="card-body">
            <form class="form-cont" action="{{route('search.trans.admin')}}" method="Get">
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search Via Trans ID" name="trans_id" value="{{isset(request()->trans_id) ? request()->trans_id: '' }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search by User ID" name="user" value="{{isset(request()->user) ? request()->user: '' }}">
                        </div>
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-block"> <i class="fa fa-search"></i> @lang('Search')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table s7__table">
                <thead>
                <tr>
                    <th> @lang('Trans ID') </th>
                    <th> @lang('User') </th>
                    <th> @lang('Details') </th>

                    <th> @lang('Time') </th>
                </tr>
                </thead>
                <tbody>
                @foreach($trans as $key => $data)
                    <tr>
                        <td>{{$data->id}}</td>
                        <td><a class="title_a" href="{{route('user.view', $data->user_id)}}">{{$data->user_id}}</a></td>
                        <td>{{$data->comment}}</td>

                        <td>{{date('d/m/y  h:i A',strtotime($data->created_at))}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{$trans->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
@endsection

