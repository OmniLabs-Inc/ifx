@extends('admin.layouts.master')
@section('title',''.$user->name)
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="col-md-12">
                <div class="card mb-5">
                    <div class="card-header">
                        <div class="card-title"><i class="fa fa-user"></i> @lang('PROFILE') </div>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-12 mb-3">
                                <h5 class="bold">{{$user->name}} USER ID: {{$user->user_unique_id}}</h5>
                                <br>
                                <h3>{{$user->email}} </h3>
                            </div>
                            <div class="col-md-12">
                                <h3 class="bold">@lang('BALANCE') : {{@$user_wallet->balance}} {{$general->currency}}</h3>
                                <p class="bold">@lang('Joined') {{$user->created_at->format('d/m/y  h:i A')}}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fa fa-cogs e6fffa"></i> @lang('Operations') </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{route('add.subs.index', $user->id)}}" class="btn btn-primary w-100"> <i class="fas fa-money-bill-alt"></i> @lang('Add / Deduct balance')  </a>
                                </div>
                                <div class="col-md-6">

                                    <a href="#reset{{$user->id}}" data-bs-toggle="modal" class="btn btn-danger w-100">@lang('Password Reset')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fa fa-cogs e6fffa"></i> @lang('Wallet')
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table s7__table">
                                <thead>
                                <tr>
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Address')</th>
                                    <th scope="col">@lang('Deposit Bal')</th>
                                    <th scope="col">@lang('Income Bal')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($wallets as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->currency}}</td>
                                    <td>{{$item->balance}}</td>
                                    <td> {{$item->freeze_balance}}</td>
                                </tr>
                                <div id="active{{$item->id}}" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">@lang('Confirm Active')</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form role="form" action="{{route('adm-active-wallet.update', $item->id)}}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <h2 class="text-danger">@lang('Are you sure?')</h2>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                                                    <button type="submit" class="btn btn-danger">@lang('Confirm')</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div id="reject{{$item->id}}" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">@lang('Confirm Active')</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form role="form" action="{{route('adm-reject-wallet.update', $item->id)}}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <h2 class="text-danger">@lang('Are you sure?')</h2>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                                                    <button type="submit" class="btn btn-danger">@lang('Confirm')</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div id="reset{{$item->id}}" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">@lang('Password Reset Confirmation')</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form role="form" action="{{route('admin-password-reset.update', $item->id)}}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <h2 class="text-danger">@lang('Are you sure?')</h2>
                                                    <h3>The User password will be reset to default password: 12345678</h3>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                                                    <button type="submit" class="btn btn-danger">@lang('Confirm')</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <div class="card-title"><i class="fa fa-cog"></i> @lang('Update Profile') </div>
                        </div>
                        <div class="card-body">
                            <form action="{{route('user.detail.update', $user->id)}}" method="post">
                                {{csrf_field()}}
                                {{method_field('put')}}

                                <div class="row mb-3">
                                    <div class="form-group col-md-4 mb-2">
                                        <strong>@lang('Name')</strong>
                                        <input class="form-control" name="name" value="{{$user->name}}" type="text">
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <strong>@lang('Mobile')</strong>
                                        <input class="form-control" name="mobile" value="{{$user->mobile}}" type="text">
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <strong>@lang('Gender')</strong>
                                        <select name="gender" class="form-select">
                                            <option {{$user->gender == 1? 'selected':''}} value="1">@lang('Male')</option>
                                            <option {{$user->gender == 0? 'selected':''}} value="0">@lang('Female')</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <strong>@lang('Address')</strong>
                                        <input class="form-control" name="address" value="{{$user->address}}" type="text">
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <strong>@lang('Zip-Code')</strong>
                                        <input class="form-control" name="zip_code" value="{{$user->zip_code}}" type="text">
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <strong>@lang('City')</strong>
                                        <input class="form-control" name="city" value="{{$user->city}}" type="text">
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <strong>@lang('Country')</strong>
                                        <input class="form-control" name="country" value="{{$user->country}}" type="text">
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <strong>@lang('Status')</strong>
                                        <select name="status" class="form-select">
                                            <option {{$user->status == 1? 'selected':''}} value="1">@lang('Active')</option>
                                            <option {{$user->status == 0? 'selected':''}} value="0">@lang('Banded')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block ">@lang('Update')</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

