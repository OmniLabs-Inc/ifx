@extends('admin.layouts.master')
@section('title',__('Default Template'))
@section('content')
    <div class="card">    
        <div class="card-body">
            <table class="table s7__table">
                <thead>
                <tr>
                    <th>{{__('SL')}}</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Subject')}}</th>
                    <th>{{__('Status')}}</th>
                    <th>{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($email_templates as $key => $data)
                    <tr>
                        <td>{{$email_templates->firstItem() + $key}}</td>
                        <td>{{$data->name}}</td>
                        <td>{{$data->subj}}</td>
                        <td>@if ($data->email_status == 1)
                            <span class="badge bg-success">{{__('Active')}}</span>
                            @else
                            <span class="badge bg-warning">{{__('Disabled')}}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('email-template.edit', $data->id)}}" class="btn s7__btn-primary s7__bg-base btn-sm btn-rounded">@lang('View/Edit')</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{$email_templates->links('pagination::bootstrap-4')}} 
            </div>
        </div>
    </div>
@endsection
