@extends('admin.layouts.master')
@section('title',__('News Edit'))
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('news-area.update',$workArea->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row mb-2">
                    <div class="form-group col-md-4">
                        <label class="form-label">@lang('Icon')</label>
                        <input type="file" id="file-input" class="form-control" name="image">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div id='img_contain'>
                                    <img id="image-preview" class="img-fluid" align='middle' src="{{asset('public/images/news/'.$workArea->image)}}" alt="your image" title=''/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-8">
                        <div class="row">
                            <div class="form-group col-md-12 mb-2">
                                <label class="form-label">@lang('Title')</label>
                                <input type="text" class="form-control" name="title" value="{{$workArea->title}}" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="form-label">@lang('Description')</label>
                                <textarea class="form-control" name="description" rows="10" required>{!! $workArea->description !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn s7__btn-primary s7__bg-base mt-2">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
@endsection
