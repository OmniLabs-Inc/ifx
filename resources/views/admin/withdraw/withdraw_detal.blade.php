@extends('admin.layouts.master')
@section('title',__('Withdraw Details'))
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table s7__table">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th><b>@lang('Detail')</b></td>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>@lang('Transaction'):</td>
                                <td>{{$data->withdraw_id}}</td>
                            </tr>
                            <tr>
                                <td>@lang('Member Name'):</td>
                                <td><a href="{{route('user.view', $data->user->id)}}">{{$data->user->name}} </a></td>
                            </tr>
        
                            <tr>
                                <td>@lang('Member Email'):</td>
                                <td>{{$data->user->email}} </td>
                            </tr>
        
                            <tr>
                                <td>@lang('Amount Of Withdraw')</td>
                                <td>{{$data->amount}} {{$general->currency}}</td>
                            </tr>
        
                            <tr>
                                <td>@lang('Charge Of Withdraw')</td>
                                <td> {{$data->charge}} {{$general->currency}}</td>
                            </tr>
        
                            <tr>
                                <td>@lang('Withdraw Method')</td>
                                <td> <b>{{$data->method_name}} </b></td>
                            </tr>
        
                            <tr>
                                <td>@lang('Given Processing Time')</td>
                                <td> {{$data->processing_time}} {{__('Days')}}</td>
                            </tr>
        
                            <tr>
                                <td>@lang('Amount In Method Currency')</td>
                                <td>  {{$data->method_cur}}</td>
                            </tr>
        
                            <tr>
                                <td>@lang('Date Of Create')</td>
                                <td>  {{ date('g:ia \o\n l jS F Y', strtotime($data->created_at)) }}</td>
                            </tr>
        
                            <tr>
                                <td>@lang('Detail')</td>
                                <td> {{$data->detail }}</td>
                            </tr>
        
                            <tr>
                                <td>@lang('Status')</td>
                                <td>
                                    @if($data->status == 0)
                                        <span class="badge bg-warning">@lang('Pending')</span>
                                    @elseif($data->status == 1)
                                        <span class="badge bg-success">@lang('Paid')</span>
                                    @else
                                        <span class="badge bg-danger">@lang('Refunded')</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
                
                @if($data->status == 0)
                <div class="col-md-6">
                    <p class="text-danger">@lang('Charge Already taken. Send') {{floatval($data->amount) * floatval($data->method_rate) }} {{$data->method_cur}} @lang('To The User')</p>
                    <form method="post" action="{{route('withdraw.process', $data->id)}}">
                        {{csrf_field()}}
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <strong >@lang('Message')</strong>
                                        <textarea class="form-control" name="message" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="status" value="1" class="btn btn-sm btn-success pull-left">@lang('Processed')</button>
                                <button type="submit" name="status"  value="3" class="btn btn-sm btn-danger pull-end">@lang('Refund')</button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                function disableBack() { window.history.forward() }
                window.onload = disableBack();
                window.onpageshow = function(evt) { if (evt.persisted) disableBack() }
            });
        })(jQuery);
    </script>
@endsection