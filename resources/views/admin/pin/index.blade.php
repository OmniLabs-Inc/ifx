@extends('admin.layouts.master')
@section('title',__('E-Pins Management'))
@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <div class="card-title uppercase bold"><i class="fa fa-search"></i> @lang('Search E-Pin')</div>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-4">

                    <button class="btn btn-outline btn-primary addPin"><i class="las la-paper-plane"></i>@lang('Create Pins')</button>

                </div>
                <div class="col-md-4">
                    <form class="form-horizontal" method="GET" action="{{route('username.search')}}">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" required placeholder="@lang('Search By User')" aria-describedby="basic-addon2">
                        <button class="btn btn-outline-primary" type="submit">@lang('Search')</button>
                    </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <form class="form-horizontal" method="GET" action="{{route('email.search')}}">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email"  placeholder="@lang('Search By Voucher')" aria-describedby="basic-addon1">
                            <button class="btn btn-outline-primary" type="submit">@lang('Search')</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa fa-user"></i> @lang('All E-Pins List')</div>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm s7__table">
                <thead>
                <tr>
                    <th>@lang('Sl')</th>
                    <th>@lang('Created by') </th>
                    <th>@lang('Used by') </th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Pin')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Creations Date')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pins as $pin)

                    <tr>
                        <td>{{ __($pin->id) }}</td>
                        <td>
                            {{ __($pin->created_by) }}

                        </td>
                        <td>{{$pin->user_id}}</td>
                        <td><b>{{$pin->amount}}</b></td>
                        <td>{{ $pin->pin}}</td>
                        <td>@if ($pin->status == 1)
                            <span class="btn btn-sm btn-success">@lang('Used')</span>
                            <br>
                            {{ diffForHumans($pin->updated_at) }}
                        @elseif($pin->status == 0)
                            <span class="btn btn-sm btn-success">@lang('Valid')</span>
                        @endif</td>
                        <td>
                            {{ showDateTime($pin->created_at) }} <br>
                            {{ diffForHumans($pin->created_at) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">

            </div>
        </div>
    </div>


    <div id="addModalPin" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Created Pin')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.pin.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group mb-3">
                                <input type="number" id="amount" class="form-control" placeholder="@lang('Enter Amount')"
                                    name="amount" aria-label="Recipient's username" aria-describedby="basic-addon2" step="any" required="">
                                <div class="input-group-text">
                                    {{ __($general->cur_text) }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Total Number of Pin')</label>
                            <input type="number" class="form-control" name="number" placeholder="@lang('Enter Number')"
                                required="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100 h-45">@lang('Generate E-pins')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@push('script')
    <script>
        (function($) {
            //"use strict";
            $('.addPin').on('click', function() {
                $('#addModalPin').modal('show');
            });
        })(jQuery);
    </script>
@endpush
