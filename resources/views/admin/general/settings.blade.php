@extends('admin.layouts.master')
@section('title',__('Settings'))
@section('content')
<div class="card">
    <div class="card-body">
        <form class="form-cont" action="{{route('general.store')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">@lang('Website Name')</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="web_name" value="{{$general->web_name}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('Website Currency (Ex: USD, EURO)')</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="currency" value="{{$general->currency}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('Color Code (Note: Do not use "#")')</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="color_code" value="{{$general->color_code}}">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold">@lang('Paginate Per Page')</label>
                    <input type="text" name="paginate" value="{{ old('paginate') ?? $general->paginate ?? '2' }}"
                            required="required" class="form-control ">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('Master Wallet Address')</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="bal_trans_fixed_charge" value="{{$general->bal_trans_fixed_charge}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('Default Blockchain Network')</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="bal_trans_percentage_charge" value="{{$general->bal_trans_percentage_charge}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('Contact Email')</label>
                    <div class="input-group">
                        <input type="email" class="form-control" name="contact_email" value="{{$general->contact_email}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('Contact Phone')</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="contact_phone" value="{{$general->contact_phone}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('Contact Address')</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="contact_address" value="{{$general->contact_address}}">
                    </div>
                </div>





            </div>
            <br>{{$general->copyright_text}}
            <div class="text-center">
                <button type="submit" class="btn s7__btn-primary s7__bg-base mt-2">{{__('Update')}}</button>
            </div>
        </form>
    </div>
</div>
@endsection

