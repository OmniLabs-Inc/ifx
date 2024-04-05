@extends('admin.layouts.master')
@section('title',__('Manage Theme'))
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            @foreach ($theme as $key => $data)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-dark p-3 text-white my-1 font-weight-bold">
                        {{$data['title']}}
                    </div>
                    <div class="card-body">
                        <img class="w-100" src="{{asset($data['path'])}}" alt="@lang('theme-image')" >
                    </div>
                    <div class="card-footer">
                        @if ($general->theme == $key)
                            <button
                                type="button"
                                disabled
                                class="btn btn-rounded btn-success btn-block mt-3">
                                <span><i class="fas fa-check-circle pr-2"></i> @lang('Active')</span>
                            </button>
                        @else
                            <button
                                type="button"
                                class="btn btn-rounded btn-danger btn-block mt-3 activateBtn"
                                data-bs-toggle="modal" data-bs-target="#activateModal"
                                data-route="{{route('activate.themeUpdate', $key)}}">
                                <span><i class="fas fa-save pr-2"></i> @lang('Inactive')</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

<div id="activateModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Theme Activate Confirmation')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>@lang('Are you sure to activate this theme?')</p>
            </div>
            <form action="" method="post" class="actionForm">
                @csrf
                @method('put')
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Activate')</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        "use strict";
        $(document).ready(function () {

            $('.activateBtn').on('click', function () {
                $('.actionForm').attr('action', $(this).data('route'));
            })

            $('select').select2({
                selectOnClose: true
            });
        });
    </script>
@endsection