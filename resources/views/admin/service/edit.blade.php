@extends('admin.layouts.master')
@section('title','Service Edit')
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="media mb-4 text-end">
                <a href="{{route('service-area.index')}}" class="btn btn-sm  btn-primary mr-2">
                    <span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
                </a>
            </div>
            <form action="{{route('service-area.update',$workArea->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="form-label">{{__('Icon')}} <small>{{__('(PNG format is standard)')}}</small></label>
                        <input type="file" id="file-input" class="form-control" name="icon">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div id='img_contain'>
                                    <img id="image-preview" class="img-fluid" align='middle' src="{{asset('public/images/service/'.$workArea->icon)}}" alt="your image" title=''/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <div class="row">
                            <div class="form-group col-md-12 mb-2">
                                <label class="form-label">{{__('Title')}}</label>
                                <input type="text" class="form-control" name="title" value="{{$workArea->title}}" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="form-label">{{__('Description')}}</label>
                                <textarea class="form-control" name="description" rows="10" required>{!! $workArea->description !!}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="text-center">
                    <button type="submit" class="btn s7__btn-primary s7__bg-base mt-2">{{__('Update')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
