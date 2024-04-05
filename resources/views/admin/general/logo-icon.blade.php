@extends('admin.layouts.master')
@section('title',__('Logo-icon'))
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>@lang('Logo-Icon')</h4>
        </div>
        <div class="card-body">
            <form action="{{route('general.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>@lang('Logo')</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group">

                                        <input type="file" id="file-input" class="form-control" name="logo">
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div id='img_contain'>
                                                    <img id="image-preview" class="img-fluid" align='middle' src="{{asset('images/logo/logo.png')}}" alt="your image" title=''/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>@lang('Dashboard Banner')</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group">

                                        <input type="file" id="file-input" class="form-control" name="footer_logo">
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div id='img_contain'>
                                                    <img id="image-preview" class="img-fluid" align='middle' src="{{asset('images/logo/footer_logo.png')}}" alt="your image" title=''/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>@lang('Icon')</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group">
                                        <input type="file" id="file-input2" class="form-control" name="favicon">
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div id='img_contain2'>
                                                    <img id="image-preview2" class="img-fluid" align='middle' src="{{asset('images/logo/favicon.png')}}" alt="your image" title=''/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-2">@lang('Update')</button>
                </div>
            </form>

        </div>
    </div>
@endsection
