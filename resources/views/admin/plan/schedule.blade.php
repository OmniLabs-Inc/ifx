@extends('admin.layouts.master')
@section('title',__('Schedule'))
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>@lang('Schedule List') <a href="#addModal" data-bs-toggle="modal" class="btn btn-success btn-sm float-end"><i class="fa fa-plus"></i> @lang('Add New')</a> </h4>
        </div>

        <div class="card-body p-0">
            <table class="table s7__table">
                <thead>
                <tr>
                    <th>@lang('Name')</th>
                    <th>@lang('Duration')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($manageTime as $data)
                <tr>
                    <td>{{$data->name}}</td>
                    <td>@lang('Time'): @lang($data->time) @lang('Hours')</td>
                    <td>
                        <a href="#editModal{{$data->id}}" data-bs-toggle="modal" class="btn s7__btn-primary s7__bg-base btn-sm editBtn">@lang('View/Edit')</a>
                    </td>
                </tr>
                <div id="editModal{{$data->id}}" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">@lang('Edit Schedule')</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="Form" action="{{route('update.schedule',$data->id)}}" method="post">
                                @csrf
                                @method('put')
                                <div class="modal-body">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                            <input type="text" name="name" value="{{$data->name}}" class="edit-name form-control form-control-lg">
                                        </div>
                
                                        <div class="form-group">
                                            <label>@lang('Time')</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name="time" value="{{$data->time}}"
                                                class="edit-time form-control form-control-lg">
                                                <span class="input-group-text">{{trans('Hour')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="addModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Social')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  role="form" action="{{route('store.schedule')}}" method="post">
                    @csrf
                    <div class="modal-body">
                       <div class="form-row">
                            <div class="form-group">
                                <label>@lang('Name')</label>
                                <input type="text" name="name" value="{{old('name')}}" class="form-control form-control-lg">
                            </div>

                            <div class="form-group">
                                <label>@lang('Time')</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="time" value="{{old('time')}}"
                                        class="form-control form-control-lg">
                                        <span class="input-group-text">{{trans('Hour')}}</span>
                                </div>
                            </div>
                       </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

