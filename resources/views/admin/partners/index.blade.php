@extends('admin.layouts.master')
@section('title',__('Partners'))
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>@lang('Partners List')<a href="#addModal" data-bs-toggle="modal" class="btn btn-success btn-sm float-end"><i class="fa fa-plus"></i> @lang('Add New')</a> </h4>
        </div>

        <div class="card-body p-0">
            <table class="table s7__table">
                <thead>
                <tr>
                    <th>@lang('Image')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($works as $data)
                <tr>
                    <td>
                        <img width="50" align='middle' src="{{asset('public/images/partner/'.$data->image)}}" alt="your image"/>
                    </td>
                    <td>
                        <a href="#editModal" data-bs-toggle="modal" data-route="{{route('partner-area.update',$data->id)}}" data-image="{{asset('public/images/partner/'.$data->image)}}" class="btn s7__btn-primary s7__bg-base btn-sm editBtn">@lang('View/Edit')</a>
                        <a href="#delModal" data-route="{{route('partner-area.delete', $data->id)}}" data-bs-toggle="modal" class="btn btn-danger btn-sm editButton">@lang('Delete')</a>
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

    <div id="addModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Partner Logo')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  role="form" action="{{route('partner-area.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                       <div class="form-row">
                           <div class="form-group col-md-12">
                               <label >@lang('Image') <small>@lang('(PNG format is standard)')</small></label>
                               <input type="file" id="file-input" class="form-control" name="image" required>
                               <div class="row mt-2">
                                   <div class="col-md-12">
                                       <div id='img_contain'>
                                           <img id="image-preview" class="img-fluid" align='middle' src="{{asset('public/images/no-image.png')}}" alt="your image" title=''/>
                                       </div>
                                   </div>
                               </div>
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
                    <h5 class="modal-title">@lang('Edit Partner Logo')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPartner" role="form" action="" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label >@lang('Image') <small>@lang('(PNG format is standard)')</small></label>
                                <input type="file" id="file-input2" class="form-control" name="image" required>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div id='img_contain2'>
                                            <img id="image-preview2" class="img-fluid" align='middle' src="" alt="your image" title=''/>
                                        </div>
                                    </div>
                                </div>
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
