@extends('admin.layouts.master')
@section('title',__('Plans'))
@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <h4>@lang('Title & Subtitle')</h4>
        </div>
        <div class="card-body">
            <form action="{{route('general.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4 mb-2">
                        <label class="form-label">@lang('Header Text')</label>
                        <input type="text" class="form-control" name="invest_head" value="{{$general->invest_head}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="form-label">@lang('Title Text')</label>
                        <input type="text" class="form-control" name="invest_title" value="{{$general->invest_title}}">
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label">@lang('Description Text')</label>
                        <input type="text" class="form-control" name="invest_description" value="{{$general->invest_description}}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">@lang('Update')</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>@lang('Plan List') <a href="{{route('plan-area.create')}}" class="btn btn-success btn-sm float-end"><i class="fa fa-plus"></i> @lang('Add New')</a> </h4>
        </div>

        <div class="card-body">
            <table class="table s7__table">
                <thead>
                <tr>
                    <th>@lang('Plan Name')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Return Percentage')</th>
                    <th>@lang('Return Time')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($works as $data)
                <tr>
                    <td>{{$data->name}}</td>
                    <td>
                        @if($data->return_time_status == 1)
                            {{$data->min_amount}} {{$general->currency}} -- {{$data->max_amount}} {{$general->currency}}
                        @elseif($data->return_time_status == 2)
                            {{$data->min_amount}} {{$general->cp_currency}} -- {{$data->max_amount}} {{$general->cp_currency}}
                        @elseif($data->return_time_status == 0)
                            Fixed {{$data->fixed_amount}} {{$general->currency}}
                        @endif
                    </td>
                    <td>{{$data->percent}}%</td>
                    <td>
                        @if(is_null($data->action))
                            <span class="badge bg-success">@lang('LIFETIME')</span>
                        @else
                            {{$data->action}} @lang('TIMES')
                        @endif
                    </td>
                    <td>
                        <a href="{{route('plan-area.edit', $data->id)}}" class="btn s7__btn-primary s7__bg-base btn-sm">@lang('View/Edit')</a>
                        <a href="#delModal" data-route="{{route('plan-area.delete', $data->id)}}" data-bs-toggle="modal" class="btn btn-danger btn-sm editButton">@lang('Delete')</a>
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
                        <h2  class="text-danger">@lang('Are you sure?')</h2>
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
