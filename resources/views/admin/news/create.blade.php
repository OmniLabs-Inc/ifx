@extends('admin.layouts.master')
@section('title',__('News Create'))
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('news-area.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="form-label">@lang('Icon')</label>
                        <input type="file" id="file-input" class="form-control" name="image" required>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div id='img_contain'>
                                    <img id="image-preview" class="img-fluid" align='middle' src="{{asset('public/images/no-image.png')}}" alt="your image" title=''/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-8 mb-2">
                        <div class="row">
                            <div class="form-group col-md-12 mb-2">
                                <label class="form-label">@lang('Title')</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="form-label">@lang('Description')</label>
                                <textarea class="form-control" name="description" rows="10" required></textarea>
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