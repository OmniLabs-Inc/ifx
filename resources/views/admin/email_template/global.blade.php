@extends('admin.layouts.master')
@section('title',__('Global Template'))
@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <h2>@lang('Short Code')</h2>
        </div>
        <div class="card-body p-0">
            <table class="table s7__table">
                <thead>
                <tr>
                    <th> # </th>
                    <th> @lang('CODE') </th>
                    <th> @lang('DESCRIPTION') </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td> 1 </td>
                    <td> <pre>&#123;&#123;message&#125;&#125;</pre> </td>
                    <td> @lang('Details Text From Script')</td>
                </tr>

                <tr>
                    <td> 2 </td>
                    <td> <pre>&#123;&#123;name&#125;&#125;</pre> </td>
                    <td> @lang('Users Name. Will Pull From Database and Use in EMAIL text')</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h4>@lang('Email Template')</h4>
        </div>
        <div class="card-body">
            <form class="form-cont" action="{{route('general.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label>@lang('Email Sent Form')</label>
                        <div class="input-group">
                            <input type="email" name="esender" class="form-control" value="{{$general->esender}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>@lang('Email Message')</label>
                        <div class="input-group">
                            <textarea class="form-control" name="email_template" rows="10">{{$general->email_template}}</textarea>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn s7__btn-primary s7__bg-base mt-2">@lang('Update')</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection