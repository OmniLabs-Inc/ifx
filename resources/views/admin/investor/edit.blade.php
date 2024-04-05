@extends('admin.layouts.master')
@section('title',__('Investor Edit'))
@section('content')
    <div class="card">
        <div class="card-header">
            <h2>@lang('Edit')</h2>
        </div>
        <div class="card-body">
            <form action="{{route('investor-area.update',$workArea->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <div class="form-group col-md-6">
                        <label class="form-label">@lang('Icon') <small>@lang('(PNG format is standard)')</small></label>
                        <input type="file" id="file-input" class="form-control" name="image">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div id='img_contain'>
                                    <img id="image-preview" class="img-fluid" align='middle' src="{{asset('public/images/investor/'.$workArea->image)}}" alt="your image" title=''/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <div class="form-group col-md-12 mb-2">
                                <label class="form-label">@lang('Name')</label>
                                <input type="text" class="form-control" name="name" value="{{$workArea->name}}" required>
                            </div>

                            <div class="form-group col-md-12 mb-2">
                                <label class="form-label">@lang('Designation')</label>
                                <input type="text" class="form-control" name="designation" value="{{$workArea->designation}}" required>
                            </div>

                            <div class="form-group col-md-12 mb-2">
                                <label class="form-label">@lang('Facebook Profile Link (If Have)')</label>
                                <input type="text" class="form-control" name="fb_link" value="{{$workArea->fb_link}}">
                            </div>

                            <div class="form-group col-md-12 mb-2">
                                <label class="form-label">@lang('Twitter Profile Link (If Have)')</label>
                                <input type="text" class="form-control" name="twitter_link" value="{{$workArea->twitter_link}}">
                            </div>

                            <div class="form-group col-md-12 mb-2">
                                <label class="form-label">@lang('Linkedin Profile Link (If Have)')</label>
                                <input type="text" class="form-control" name="linkedin_link" value="{{$workArea->linkedin_link}}">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="text-center">
                    <button type="submit" class="btn s7__btn-primary s7__bg-base mt-2">@lang('Update')</button>
                </div>
            </form>
        </div>
    </div>
@endsection
