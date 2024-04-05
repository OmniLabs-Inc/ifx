@extends('admin.layouts.master')
@section('title',__('Plan Edit'))
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('plan-area.update',$workArea->id)}}" method="POST">
                @csrf
                @method('put')

                <div class="row mb-2">
                    <div class="form-group col-md-4">
                        <label class="form-label">@lang('Plan Name')</label>
                        <input type="text" class="form-control" name="name" value="{{$workArea->name}}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="form-label">@lang('Plan Type')</label>
                        <select name="return_time_status" id="planType" class="form-select">
                            <option disabled  value="">@lang('Choose One')</option>
                            <option {{$workArea->return_time_status == 1? 'selected' : ''}} value="1">@lang('ROI Invest')</option>
                            <option {{$workArea->return_time_status == 0? 'selected' : ''}} value="0">@lang('Fixed Invest')</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>@lang('Capital Back Status')</label>
                        <select name="capital_back_status" class="form-select">
                            <option disabled selected value="">@lang('Choose One')</option>
                            <option {{$workArea->capital_back_status == 0? 'selected' : ''}} value="0">@lang('Capital Not Back')</option>
                            <option {{$workArea->capital_back_status == 1? 'selected' : ''}} value="1">@lang('Capital Back')</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="form-group col-md-6">
                        <label class="form-label">@lang('Return Percentage')</label>
                        <input type="text" class="form-control" name="percent" required value="{{$workArea->percent}}">
                    </div>

                    <div class="form-group col-md-6">
                        <label class="form-label">@lang('Return Period')</label>
                        <select name="period" class="form-select" required>
                            <option value="" disabled>@lang('Select a Period')</option>
                            @foreach($times as $item)
                                <option value="{{$item->time}}"
                                    {{ old('schedule', $workArea->period) == $item->time ? 'selected' : '' }}>
                                    @lang('Every') {{$item->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="roiInvest mb-2">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-label">@lang('Minimum Amount')</label>
                            <input type="text" class="form-control" value="{{$workArea->min_amount}}" name="min_amount">
                        </div>

                        <div class="form-group col-md-6 mb-2">
                            <label class="form-label">@lang('Maximum Amount')</label>
                            <input type="text" class="form-control" name="max_amount" value="{{$workArea->max_amount}}">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">@lang('Return Action (How many time)')</label>
                            <input type="number" class="form-control" name="action" value="{{$workArea->action}}">
                        </div>
                    </div>
                </div>
                <div class="fixedInvest mb-2">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-label">@lang('Fixed Amount (Return Action #Liftime)')</label>
                            <input type="text" class="form-control" name="fixed_amount" value="{{$workArea->fixed_amount}}">
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn s7__btn-primary s7__bg-base mt-2">@lang('Update')</button>
                </div>
            </form>
        </div>
    </div>
    <input type="hidden" id="return_time_status" value="{{$workArea->return_time_status}}">
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script>
        (function($) {
            "use strict";
            $(window).load(function() {

                var myReturnTime = $('#return_time_status').val();

                makeAction(''+myReturnTime+'');
                console.log(makeAction);
            });
            $(document).ready(function () {
                $('#planType').on('change',function () {
                    makeAction(this.value);
                });
            });
            function makeAction(value) {
                if (value == 1){
                    $('.fixedInvest').css('display','none');
                    $('.roiInvest').css('display','block');
                }
                if (value == 0){
                    $('.roiInvest').css('display','none');
                    $('.fixedInvest').css('display','block');
                }
            }
        })(jQuery);
    </script>
@endsection
