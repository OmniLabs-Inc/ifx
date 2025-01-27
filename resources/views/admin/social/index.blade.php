@extends('admin.layouts.master')
@section('title',__('Social'))
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>@lang('Socials List') <a href="#addModal" data-bs-toggle="modal" class="btn btn-success btn-sm float-end"><i class="fa fa-plus"></i> @lang('Add New')</a> </h4>
        </div>

        <div class="card-body p-0">
                <table class="table s7__table">
                    <thead>
                    <tr>
                        <th>@lang('Icon')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($works as $data)
                    <tr>
                        <td>
                            <a href="{{$data->link}}" target="_blank"><i class="fab fa-{{$data->icon}}"></i></a>
                        </td>
                        <td>
                            <a href="#editModal" data-bs-toggle="modal" data-route="{{route('social-area.update',$data->id)}}" data-icon="{{$data->icon}}" data-link="{{$data->link}}" class="btn s7__btn-primary s7__bg-base editBtn">@lang('View/Edit')</a>
                            <a href="#delModal" data-route="{{route('social-area.delete', $data->id)}}" data-bs-toggle="modal" class="btn btn-danger btn-sm editButton">@lang('Delete')</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

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

    <div id="addModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Social')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  role="form" action="{{route('social-area.store')}}" method="post">
                    @csrf
                    <div class="modal-body">
                       <div class="form-row">
                           <div class="form-group col-md-12">
                               <label class="form-label">@lang('Icon (Ex:facebook)')</label>
                               <input type="text" class="form-control" name="icon" required>
                           </div>

                           <div class="form-group col-md-12">
                               <label class="form-label">@lang('Link or url')</label>
                               <input type="text" class="form-control" name="link" required>
                           </div>
                       </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit Social')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPartner" role="form" action="" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>@lang('Icon (Ex:facebook)')</label>
                                <input type="text" id="editIcon" class="form-control" name="icon" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label >@lang('Link or url')</label>
                                <input type="text" id="editUrl" class="form-control" name="link" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $('.editBtn').on('click',function () {
                $('#editPartner').attr('action',$(this).data('route'));
                $('#editIcon').val($(this).data('icon'));
                $('#editUrl').val($(this).data('link'));

            });
        });
	})(jQuery);
    </script>
@endsection
