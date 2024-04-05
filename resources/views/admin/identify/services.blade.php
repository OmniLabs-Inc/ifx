@extends('admin.layouts.master')
@section('title',__('Identity Form'))
@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="post" action="{{route('general.store')}}" class="form-row align-items-center ">
            @csrf
            <div class="row">    
                <div class="form-group col-md-3">
                    <label class="d-block">@lang('Identity Verification')</label>
                    <div class="onOff-radio-field">
                        <div class="onOff-radio-option">
                            <input type="radio" name="identity_verification" id="identity_verification" value="1" <?php if ($general->identity_verification == 1):echo 'checked'; endif ?>>
                            <label class="bgc-success" for="identity_verification">ON</label>
                        </div>

                        <div class="onOff-radio-option">
                            <input type="radio" name="identity_verification" id="identity_verification1" value="0" <?php if ($general->identity_verification == 0):echo 'checked'; endif ?>>
                            <label class="bgc-danger" for="identity_verification1">OFF</label>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <button type="submit" class="btn s7__btn-primary s7__bg-base text-white mx-2 mt-4">
                        <span>@lang('Submit')</span></button>
                </div>
            </div>

        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        @php
            $newArr = ['Driving License','Passport','National ID'];
        @endphp
        @if(count($formExist) != count($newArr) )
        <h4 class="card-title">@lang('Identity Form') <button data-bs-toggle="modal" data-bs-target="#btn_add" type="button" class="btn s7__btn-primary s7__bg-base float-end"><i
            class="fa fa-plus-circle"></i> {{trans('Add Form')}} </button></h4>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table s7__table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('Identity Type')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($forms as $key => $data)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$data->name}}</td>
                    <td>
                        <span
                        class="badge bg-{{ $data->status == 0 ? 'danger' : 'success' }}">{{ $data->status == 0 ? 'Inactive' : 'Active' }}</span>
                    </td>
                    <td data-label="@lang('Action')">
                        <a href="javascript:void(0)"
                            data-id="{{$data->id}}"
                            data-name="{{$data->name}}"
                            data-status="{{$data->status}}"
                            data-services_form="{{($data->services_form == null) ? null :  json_encode(@$data->services_form)}}"
                            data-bs-toggle="modal" data-bs-target="#editModal" data-original-title="Edit"
                            class="btn s7__btn-primary s7__bg-base text-white btn-sm editButton"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="btn_add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" method="post">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="myModalLabel"><i class="fa fa-plus-circle"></i> {{trans('Add New')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-md-6 form-group">
                        <label for="inputName"><strong>{{trans('Identity Type')}} :</strong></label>
                        <select class="form-select w-100"
                                data-live-search="true" name="name"
                                required="">
                            <option disabled selected>@lang("Select Type")</option>
                            @if(!in_array('Driving License', $formExist))
                                <option value="Driving License" >{{trans('Driving License')}}</option>
                            @endif
                            @if(!in_array('Passport', $formExist))
                                <option value="Passport">{{trans('Passport')}}</option>
                            @endif
                            @if(!in_array('National ID', $formExist))
                                <option value="National ID">{{trans('National ID')}}</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="inputName" class="d-block"><strong>{{trans('Status')}}
                                :</strong></label>

                        <select class="form-select w-100"
                                data-live-search="true" name="status"
                                required="">
                            <option disabled selected>@lang("Select Status")</option>
                            <option value="1">{{trans('Active')}}</option>
                            <option value="0">{{trans('Deactive')}}</option>
                        </select>
                    </div>

                    <div class="col-md-12 form-group">
                        <br>
                        <a href="javascript:void(0)" class="btn btn-success btn-sm float-end generate"><i
                                class="fa fa-plus-circle"></i> {{trans('Add Field')}}</a>

                    </div>
                </div>

                <div class="row addedField mt-3">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('Close')}}</button>
                <button type="submit" class="btn btn-primary">{{trans('Submit')}}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" method="post">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="myModalLabel"><i class="fa fa-plus-circle"></i> {{trans('Update Identity Form')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-md-6 form-group d-none">
                        <label for="inputName" class="control-label"><strong>{{trans('Identity Type')}}
                                :</strong></label>

                        <select class="form-select w-100 edit_name d-none"
                                data-live-search="true" name="name"
                                required="">
                            <option disabled selected>@lang("Select Type")</option>
                            <option value="Driving License">{{trans('Driving License')}}</option>
                            <option value="Passport">{{trans('Passport')}}</option>
                            <option value="National ID">{{trans('National ID')}}</option>
                        </select>
                        <br>
                        <br>
                        @error('name')
                        <span class="text-danger">{{ trans($message) }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group ">
                        <label for="inputName" class="control-label d-block"><strong>{{trans('Status')}}
                                :</strong></label>
                        <select class="form-select  w-100 edit_status"
                                data-live-search="true" name="status"
                                required="">
                            <option disabled>@lang("Select Status")</option>
                            <option value="1">{{trans('Active')}}</option>
                            <option value="0">{{trans('Deactive')}}</option>
                        </select>
                        <br>
                        @error('status')
                        <span class="text-danger">{{ trans($message) }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <br>
                        <a href="javascript:void(0)" class="btn btn-success btn-sm float-right generate"><i
                                class="fa fa-plus-circle"></i> {{trans('Add Field')}}</a>

                    </div>
                </div>

                <div class="row addedField mt-3">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('Close')}}</button>
                <button type="submit" class="btn s7__btn-primary s7__bg-base">{{trans('Update')}}</button>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
    <script>
        "use strict";
        $(document).ready(function () {

            $(".generate").on('click', function () {
                var form = `<div class="col-md-12 mb-3">
                                 <div class="card border-primary">
                                        <div class="card-header  bg-primary p-2 d-flex justify-content-between">
                                            <h5 class="card-title text-white font-weight-bold">{{trans('Field information')}}</h3>
                                            <button  class="btn  btn-danger btn-sm delete_desc" type="button">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Name')}}</label>
                                                    <input name="field_name[]" class="form-control " type="text" value="" required
                                                           placeholder="{{trans('Field Name')}}">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Form Type')}}</label>
                                                    <select name="type[]" class="form-select">
                                                        <option value="text">{{trans('Input Text')}}</option>
                                                        <option value="textarea">{{trans('Textarea')}}</option>
                                                        <option value="file">{{trans('File upload')}}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Length')}}</label>
                                                    <input name="field_length[]" class="form-control " type="number" min="2" value="" required
                                                           placeholder="{{trans('Length')}}">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Length Type')}}</label>
                                                    <select name="length_type[]" class="form-select">
                                                        <option value="max">{{trans('Maximum Length')}}</option>
                                                        <option value="digits">{{trans('Fixed Length')}}</option>
                                                    </select>
                                                </div>



                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Form Validation')}}</label>
                                                    <select name="validation[]" class="form-select">
                                                        <option value="required">{{trans('Required')}}</option>
                                                        <option value="nullable">{{trans('Optional')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                            </div> `;

                $('.addedField').append(form)
            });

            $('select').select2({
                width: '100%'
            });


            $(document).on('click', '.editButton', function (e) {
                $('.addedField').html('')

                var obj = $(this).data()
                $('.edit_id').val(obj.id)
                $('.edit_name').val(obj.name).trigger('change');
                $(".edit_status").val(obj.status).trigger('change');
                $(".edit_service_id").val(obj.service_id).trigger('change');

                if (obj.services_form == 'null') {

                } else {
                    var objData = Object.entries(obj.services_form);

                    var form = '';
                    for (let i = 0; i < objData.length; i++) {
                        form += `<div class="col-md-12 mb-3">

                                    <div class="card border-primary">

                                        <div class="card-header  bg-primary p-2 d-flex justify-content-between">
                                            <h5 class="card-title text-white font-weight-bold">{{trans('Field information')}}</h3>
                                            <button  class="btn  btn-danger btn-sm delete_desc" type="button">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Name')}}</label>
                                                    <input name="field_name[]" class="form-control"
                                                     value="${objData[i][1].field_level}"
                                                     type="text" required
                                                           placeholder="{{trans('Field Name')}}">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Form Type')}}</label>
                                                    <select name="type[]" class="form-select">
                                                        <option value="text" ${(objData[i][1].type === 'text' ? 'selected="selected"' : '')}>{{trans('Input Text')}}</option>
                                                        <option value="textarea" ${(objData[i][1].type === 'textarea' ? 'selected="selected"' : '')}>{{trans('Textarea')}}</option>
                                                        <option value="file" ${(objData[i][1].type === 'file' ? 'selected="selected"' : '')}>{{trans('File upload')}}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Length')}}</label>
                                                    <input name="field_length[]" class="form-control " type="number" min="2" required
                                                    value="${objData[i][1].field_length}"
                                                           placeholder="{{trans('Length')}}">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Field Length Type')}}</label>
                                                    <select name="length_type[]" class="form-select">
                                                        <option value="max"  ${(objData[i][1].length_type === 'max' ? 'selected="selected"' : '')}>{{trans('Maximum Length')}}</option>
                                                        <option value="digits"  ${(objData[i][1].length_type === 'digits' ? 'selected="selected"' : '')}>{{trans('Fixed Length')}}</option>
                                                    </select>
                                                </div>



                                                <div class="col-md-4 form-group">
                                                    <label>{{trans('Form Validation')}}</label>
                                                    <select name="validation[]" class="form-select">
                                                            <option value="required" ${(objData[i][1].validation === 'required' ? 'selected="selected"' : '')}>{{trans('Required')}}</option>
                                                            <option value="nullable" ${(objData[i][1].validation === 'nullable' ? 'selected="selected"' : '')}>{{trans('Optional')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> `;
                    }
                    $('.addedField').append(form)
                }
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.card').parent().remove();
            });
        });

    </script>
@endsection
