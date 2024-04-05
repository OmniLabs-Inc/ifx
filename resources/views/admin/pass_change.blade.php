@extends('admin.layouts.master')
@section('title',__('Profile'))
@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">{{__('Profile Details')}}</h5>

        <form class="mt-5" action="{{route('admin.updatePassword')}}" method="POST">
            @csrf
            <div class="row gy-4 justify-content-center">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{auth()->guard('admin')->user()->name}}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="{{auth()->guard('admin')->user()->email}}">
                    </div>
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn s7__btn-primary s7__bg-base text-white mt-4">Save Changes</button>
            </div>

        </form>
    </div>
</div>
@endsection
