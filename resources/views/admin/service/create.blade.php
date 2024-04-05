@extends('admin.layouts.master')
@section('title','Service Create')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="media mb-4 text-end">
                <a href="" class="btn btn-sm  btn-primary mr-2">
                    <span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
                </a>
            </div>
            <form action="{{route('service-area.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="form-group col-md-6">
                        <label>{{__('Image')}} <small>{{__('(PNG format is standard)')}}</small></label>
                        <input type="file" id="file-input" class="form-control" name="icon" required>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div id='img_contain'>
                                    <img id="image-preview" class="img-fluid" align='middle' src="{{asset('public/images/no-image.png')}}" alt="your image" title=''/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <div class="form-group col-md-12 mb-2">
                                <label>{{__('Title')}}</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label >{{__('Description')}}</label>
                                <textarea class="form-control" name="description" rows="10" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn s7__btn-primary s7__bg-base mt-2">{{__('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
