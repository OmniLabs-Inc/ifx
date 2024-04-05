@extends('admin.layouts.master')
@section('title',__('Plan Create'))
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('plan-area.store')}}" method="POST">
                @csrf
                <div class="row mb-2">
                    <div class="form-group col-md-4">
                        <label class="form-label">@lang('Plan Name')</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="form-label">@lang('Plan Type')</label>
                        <select name="return_time_status" id="planType" class="form-select">
                            <option disabled selected value="">@lang('Choose One')</option>
                            <option value="1">@lang('ROI Invest')</option>
                            <option value="0">@lang('Fixed Invest')</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>@lang('Capital Back')</label>
                        <select name="capital_back_status" class="form-select">
                            <option disabled selected value="">@lang('Choose One')</option>
                            <option value="0">@lang('Capital Not Back')</option>
                            <option value="1">@lang('Capital Back')</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="form-group col-md-6">
                        <label class="form-label">@lang('Return Percentage')</label>
                        <input type="text" class="form-control" name="percent" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="form-label">@lang('Return Period')</label>
                        <select name="period" class="form-select" required>
                            <option value="" selected disabled>@lang('Select a Period')</option>
                            @foreach($times as $data)
                                <option value="{{$data->time}}">@lang('Every') {{$data->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="roiInvest mb-2">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-label">@lang('Minimum Amount')</label>
                            <input type="text" class="form-control" name="min_amount">
                        </div>

                        <div class="form-group col-md-6 mb-2">
                            <label class="form-label">@lang('Maximum Amount')</label>
                            <input type="text" class="form-control" name="max_amount">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">@lang('Return Action (How many time)')</label>
                            <input type="number" class="form-control" name="action">
                        </div>
                    </div>
                </div>
                <div class="fixedInvest mb-2">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-label">@lang('Fixed Amount (Return Action #Liftime)')</label>
                            <input type="text" class="form-control" name="fixed_amount">
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
@section('script')
    <script>
        (function($) {
            "use strict";
        $(document).ready(function () {
            $('#planType').on('change',function () {
                if (this.value == 1){
                    $('.fixedInvest').css('display','none');
                    $('.roiInvest').css('display','block');
                }
                if (this.value == 0){
                    $('.roiInvest').css('display','none');
                    $('.fixedInvest').css('display','block');
                }
            })
        })
        })(jQuery);
    </script>
@endsection
