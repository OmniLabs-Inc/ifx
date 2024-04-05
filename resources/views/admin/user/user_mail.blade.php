@extends('admin.layouts.master')
@section('title','Send Mail to'.$user->name)
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa fa-envelope"></i>  @lang('Send email to') {{$user->first_name}} {{$user->last_name}}
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('send.mail.user', $user->id)}}" method="post">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><strong>@lang('SUBJECT')</strong></label>
                            <input class="form-control input-lg" name="subject"  type="text" required>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="form-group">
                            <label><strong>@lang('Message')</strong> @lang('NB: EMAIL WILL SENT USING EMAIL TEMPLATE')</label>
                            <textarea name="message" rows="10" class="form-control" id="shaons"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-block"> @lang('Send') </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

