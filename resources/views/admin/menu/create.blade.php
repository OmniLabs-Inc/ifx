@extends('admin.layouts.master')
@section('title',__('Menu Create'))
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('menu-area.store')}}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="form-group col-md-12 mb-2">
                        <label class="form-label">@lang('Title')</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-label">@lang('Description')</label>
                        <textarea class="form-control" name="description" rows="10" required></textarea>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn s7__btn-primary s7__bg-base mt-2">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
@endsection

