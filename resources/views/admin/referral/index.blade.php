@extends('admin.layouts.master')
@section('title',__('Referral'))
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>@lang('Referral Bonus Settings') 
                <button id="addLevel" class="btn btn-success float-end btn-sm"><i class="fa fa-plus"></i> @lang('Add New Level')</button>
            </h4>
        </div>

        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <p class="text-danger font-weight-bold">@lang('NOTE : Insert "Blank or remove percentage value" for delete Level Bonus.')</p>
                    <form action="{{route('admin.referral.update')}}" method="POST">
                        @csrf
                        <div class="row">
                            @foreach($ref as $data)
                                <div class="form-group col-md-12">
                                    <label >{{$data->id}} @lang('Level Bonus')</label>
                                    <div class="input-group mb-3 ">
                                        <input type="text" class="form-control" name="percentage[]" placeholder="Bonus Percentage" value="{{$data->percentage}}" aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-row" id="newLevel">
        
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn s7__btn-primary s7__bg-base mt-2">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        (function($) {
            "use strict";
            @if(($lastRef instanceof \App\Referral))
             var count = parseInt('{{$lastRef->id}}');
            @else
            var count = 0;
            @endif
            $("#addLevel").on('click',function() {
                count += 1;
                $("#newLevel").append(
                    `<div class="form-group col-md-12">
                        <label >`+count+` Level Bonus</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="percentage[]" placeholder="Bonus Percentage"  aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">%</span>
                        </div>
                    </div>`
                );
            });
        })(jQuery);
    </script>
@endsection
