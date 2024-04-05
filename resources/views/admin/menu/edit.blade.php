@extends('admin.layouts.master')
@section('title',__('Menu Edit'))
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('menu-area.update',$workArea->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <div class="form-group mb-2">
                        <label>@lang('Title')</label>
                        <input type="text" class="form-control" name="title" value="{{$workArea->title}}" required>
                    </div>

                    <div class="form-group">
                        <label >@lang('Description')</label>
                        <textarea class="form-control" name="description" rows="10" required>{!! $workArea->description !!}</textarea>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn s7__btn-primary s7__bg-base mt-2">@lang('Update')</button>
                </div>
            </form>
        </div>
    </div>
@endsection
