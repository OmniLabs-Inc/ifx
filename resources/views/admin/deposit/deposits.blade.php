@extends('admin.layouts.master')
@section('title',__('Deposit Log'))
@section('content')
    <div class="card">
        <div class="card-body p-0">
            <table class="table s7__table">
                <thead>
                <tr>
                    <th>@lang('Serial')</th>
                    <th>@lang('UserID')</th>
                    <th>@lang('Trans ID')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Currency')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Hash')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($deposits as $key => $deposit)
                    <tr>
                        <td>{{ $deposit->id }}</td>
                        <td data-label="Name"><a target="_blank" href="{{route('user.view', $deposit->user_id)}}">{{$deposit->user_id}}</a></td>
                        <td data-label="TransactionID">{{ $deposit->transaction_id }}</td>
                        <td data-label="Amount">{{round($deposit->deposit_currency_amount, 8)}} </td>
                        <td data-label="Currency">{{ $deposit->deposit_currency }} </td>

                        <td>

                            @if($deposit->status == 0)
                                <span class="badge bg-warning">@lang('pending')</span>
                            @elseif($deposit->status == 1)
                                <span class="badge bg-danger">@lang('rejected')</span>
                            @else
                                <span class="badge bg-success">@lang('confirmed')</span>
                            @endif

                        </td>
                        <td  data-label="Details">{{$deposit->created_at}}</td>
                        <td data-label="Hash">
                            @if($deposit->hash != '')
                            <a href="https://bscscan.com/tx/{{ $deposit->hash }}" target="_blank">
                                <button class="btn btn-warning">verify</button>
                            </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{$deposits->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
@endsection

