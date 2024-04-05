@extends('admin.layouts.master')
@section('title',__('Email Controls'))
@section('content')
<div class="card">
    <form action="{{route('admin.email-controls.update',$general->id)}}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label><strong>{{__('From Email')}}</strong></label>
                        <input type="text" class="form-control" name="sender_email" value="{{$general->sender_email}}" placeholder="Title">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label><strong>{{__('From Email Name')}}</strong></label>
                        <input type="text" class="form-control" name="sender_email_name" value="{{$general->sender_email_name}}" placeholder="Title">
                    </div>
                </div>
                <div class="form-group d-none">
                    <label><strong>{{__('Send Email Method')}}</strong></label>
                    <select name="email_method" class="form-select">
                        <option value="sendmail" @if(old('email_method', @$general->email_configuration->name) == "sendmail")  selected @endif>{{__('PHP Mail')}}</option>
                        <option value="smtp" @if( old('email_method', @$general->email_configuration->name) == "smtp") selected @endif>{{__('SMTP')}}</option>
                    </select>
                </div>
            </div>
            <div class="row d-none configForm mb-3" id="smtp">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label><strong>{{__('Host')}} <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" placeholder="{{__('Host or Email Address')}}" name="smtp_host" value="{{ old('smtp_host', $general->email_configuration->smtp_host ?? '') }}"/>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label><strong>{{__('Port')}} <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" placeholder="{{__('Available port')}}" name="smtp_port" value="{{ old('smtp_port', $general->email_configuration->smtp_port ?? '') }}"/>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label><strong>{{__('Encryption')}} <span class="text-danger">*</span></strong></label>
                        <select name="smtp_encryption" class="form-select">
                            <option value="tls" @if( old('smtp_encryption', @$general->email_configuration->smtp_encryption) == "tls") selected @endif>{{__('TLS')}}</option>
                            <option value="ssl" @if( old('smtp_encryption', @$general->email_configuration->smtp_encryption) == "ssl") selected @endif>{{__('SSL')}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label><strong>{{__('Username')}} <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" placeholder="{{__('username or Email')}}" name="smtp_username" value="{{ old('smtp_username', $general->email_configuration->smtp_username ?? '') }}"/>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label><strong>{{__('Password')}} <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" placeholder="{{__('Password')}}" name="smtp_password" value="{{ old('smtp_password', $general->email_configuration->smtp_password ?? '') }}"/>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn s7__btn-primary s7__bg-base me-2">{{__('Submit')}}</button>
            </div>
        </div>
    </form>
</div>
@endsection