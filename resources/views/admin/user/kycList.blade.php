@extends('admin.layouts.master')
@section('title',$page_title)
@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table s7__table">
            <thead>
            <tr>
                <th>@lang('No.')</th>
                <th>@lang('Name')</th>
                <th>@lang('Verification Type')</th>
                <th>@lang('Status')</th>
                <th>@lang('Action')</th>
            </tr>
            </thead>
            <tbody>
                @forelse($logs as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>
                        <a href="{{route('user.view',[$item->user_id])}}" target="_blank">
                            <div class="d-flex no-block align-items-center">
                                <div class="">
                                    <h5 class="text-dark mb-0 font-16 font-weight-medium">
                                        @lang(optional($item->user)->name)
                                    </h5>
                                    <span class="text-muted font-14"><span>@</span>@lang(optional($item->user)->email)</span>
                                </div>
                            </div>
                        </a>
                    </td>
                    <td><b>{{subroTitle($item->kyc_type)}}</b></td>
                    <td data-label="@lang('Status')">
                        @if($item->status == 0)
                            <span class="badge bg-warning">{{trans('Pending')}}</span>
                        @elseif($item->status == 1)
                            <span class="badge bg-success">{{trans('Approved')}}</span>
                        @elseif($item->status == 2)
                            <span class="badge bg-danger">{{trans('Rejected')}}</span>
                        @endif

                    </td>
                    <td data-label="@lang('Action')">
                        @php
                            if($item->details){
                                    $details =[];
                                        foreach($item->details as $k => $v){
                                            if($v->type == "file"){
                                                $details[subroTitle($k)] = [
                                                    'type' => $v->type,
                                                    'field_name' => asset('public/images/kyc/'.$v->field_name)
                                                ];
                                            }else{
                                                $details[subroTitle($k)] =[
                                                    'type' => $v->type,
                                                    'field_name' => $v->field_name
                                                ];
                                            }
                                        }
                                }else{
                                    $details = null;
                                }
                        @endphp

                        <button
                            class="edit_button  btn  {{($item->status == 0) ?  'btn-primary' : 'btn-success'}} text-white  btn-sm "
                            data-bs-toggle="modal" data-bs-target="#myModal"
                            data-title="{{($item->status == 0) ?  trans('Edit') : trans('Details')}}"

                            data-id="{{ $item->id }}"
                            data-info="{{json_encode($details)}}"
                            data-route="{{route('users.Kyc.action',$item->id)}}"
                            data-status="{{$item->status}}">

                            @if(($item->status == 0))
                                <i class="las la-edit"></i>
                            @else
                                <i class="las la-eye"></i>
                            @endif

                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center text-danger" colspan="100%">@lang('No User Data')</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12 text-center">{{$logs->links()}}</div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('KYC Information')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form role="form" method="POST" class="actionRoute" action="" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-body">
                    <ul class="list-group withdraw-detail">
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    @if(Request::routeIs('kyc.users.pending'))
                        <input type="hidden" class="action_id" name="id">
                        <button type="submit" class="btn btn-primary" name="status"
                                value="1">@lang('Approve')</button>
                        <button type="submit" class="btn btn-danger" name="status"
                                value="2">@lang('Reject')</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    "use strict";
    $(document).on("click", '.edit_button', function (e) {
        var id = $(this).data('id');

        $(".action_id").val(id);
        $(".actionRoute").attr('action', $(this).data('route'));
        var details = Object.entries($(this).data('info'));
        var list = [];
        details.map(function (item, i) {
            if (item[1].type == 'file') {
                var singleInfo = `<br><img src="${item[1].field_name}" alt="..." class="w-100">`;
            } else {
                var singleInfo = `<span class="font-weight-bold ml-3">${item[1].field_name}</span>  `;
            }
            list[i] = ` <li class="list-group-item"><span class="font-weight-bold "> ${item[0].replace('_', " ")} </span> : ${singleInfo}</li>`
        });
        $('.withdraw-detail').html(list);

    });

</script>
@endsection