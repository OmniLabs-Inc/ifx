@extends('admin.layouts.master')
@section('title',__('Service'))
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>@lang('Service List') <a href="{{route('service-area.create')}}" class="btn btn-success btn-sm float-end"><i class="fa fa-plus"></i> @lang('Add New')</a> </h4>
        </div>

        <div class="card-body p-0">
            <table class="table s7__table">
                <thead>
                <tr>
                    <th>@lang('Icon')</th>
                    <th>@lang('Title')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($works as $data)
                <tr>
                    <td>
                        <img width="50" align='middle' src="{{asset('public/images/service/'.$data->icon)}}" alt="your image"/>
                    </td>
                    <td>{{$data->title}}</td>
                    <td>
                        <a href="{{route('service-area.edit', $data->id)}}" class="btn s7__btn-primary s7__bg-base btn-sm">@lang('View/Edit')</a>
                        <a href="#delModal" data-route="{{route('service-area.delete', $data->id)}}" data-bs-toggle="modal" class="btn btn-danger btn-sm editButton">@lang('Delete')</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="delModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirm Delete')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="confirmDel" role="form" action="" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <h2 class="text-danger">@lang('Are you sure?')</h2>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-danger">@lang('Delete')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
