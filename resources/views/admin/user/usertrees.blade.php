@extends('admin.layouts.master')
@section('title',__('Stage Trees'))
@section('content')


    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa fa-user"></i> @lang('Stage ') {{$stage}} @lang('Trees ')</div>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm s7__table">
                <thead>
                <tr>
                    <th> @lang('Sl')</th>
                    <th> @lang('Upliner Name') </th>
                    <th>@lang('Left Downliner')</th>
                    <th>@lang('Right Downliner')</th>
                    <th> @lang('Last Joined')</th>
                    <th> @lang('Action') </th>
                </tr>
                </thead>
                <tbody>
                @foreach($stage_tbl as $key => $data)
                    @php $upliner = App\Models\User::find($data->user_id); @endphp
                    <tr>
                        <td>{{$key+1}}</td>
                        <td><b>{{$upliner->name}}</b></td>
                        <td>{{$data->user1_name}}</td>
                        <td>{{ $data->user2_name}}</td>
                        <td>{{ $data->updated_at}}</td>
                        <td>
                            <a class="btn s7__btn-primary s7__bg-base btn-sm" href="{{route('user.treeview',[$stage, $data->id])}}"><i class="fa fa-eye"></i>  @lang('View')</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{$user->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
@endsection

