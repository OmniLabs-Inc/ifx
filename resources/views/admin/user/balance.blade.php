@extends('admin.layouts.master')
@section('title',__('User Management'))
@section('content')
    <div class="row">
        <div class="col-md-6 mb-5">
            <div class="card">
                <div class="card-header">
                    <div class="caption uppercase bold">
                        <i class="fas fa-money-bill-alt"></i> @lang('CURRENT BALANCE OF') <strong>{{$user->name}}</strong></div>
                </div>
                <div class="card-body uppercase text-center">
                    <h3>@lang('WALLET BALANCE') </h3>
                    <h1><strong>USDT  {{ $deposit_wallet }}</strong></h1>
                </div>
                <div class="card-body uppercase text-center">
                    <h3>@lang('AVAILABLE BALANCE') </h3>
                    <h1><strong>USDT  {{ $income_wallet }}</strong></h1>
                </div>

                <div class="card-body uppercase text-center">
                    <h3>@lang('REWARD BALANCE') </h3>
                    <h1><strong>USDT  {{ $reward_wallet }}</strong></h1>
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="caption uppercase bold">
                        <i class="fa fa-cog"></i> @lang('Add/Deduct balance')
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('user.balance.update', $user->id)}}" method="post">
                        {{csrf_field()}}
                        <div class="row mb-3">
                            <div class="form-group col-12 mb-2">
                                <label><strong>@lang('OPERATION')</strong></label>
                                <select name="operation" class="form-select">
                                    <option value="1">@lang('Add Money')</option>
                                    <option value="0">@lang('Deduct Money')</option>
                                </select>
                            </div>

                            <div class="form-group col-12 mb-2">
                                <label><strong>@lang('CHOOSE WALLET')</strong></label>
                                <select name="wallet" class="form-select">
                                    <option value="1">@lang('Deposit Wallet')</option>
                                    <option value="2">@lang('Income Wallet')</option>
                                    <option value="3">@lang('Rewards Wallet')</option>
                                </select>
                            </div>
                            <div class="form-group col-12 mb-2">
                                <label><strong>@lang('Amount')</strong></label>
                                <div class="input-group ">
                                    <input type="text" class="form-control" name="amount" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2">{{$general->currency}}</span>
                                </div>
                            </div>
                            <div class="form-group col-12 mb-2">
                                <label><strong>@lang('Message')</strong></label>
                                <textarea name="message" rows="5" class="form-control"  placeholder="@lang('if any')"></textarea>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block"> @lang('Submit') </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
