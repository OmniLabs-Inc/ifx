<?php

namespace App\Http\Controllers\Admin;

use App\Models\General;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IdentifyForm;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Stevebauman\Purify\Facades\Purify;


class IdentyVerifyFromController extends Controller
{
    public function index()
    {
        $data['control'] = General::firstOrNew();
        $data['page_title'] = "Identity Form";
        $forms = IdentifyForm::get();
        $data['formExist'] = $forms->pluck('name')->toArray();
        $data['forms'] = $forms;
        return view('admin.identify.services', $data);
    }

    public function store(Request $request)
    {
        $excp = Purify::clean($request->except('_token', '_method'));

        $validate = Validator::make($request->all(), [
            'name' => ['required', Rule::in(['Driving License', 'Passport','National ID'])],
            'status' => 'required',
        ]);
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        try {
            $data = IdentifyForm::firstOrNew([
                'slug' => slug($excp['name'])
            ]);
            $data->name = $excp['name'];
            $data->status = (int)$excp['status'];
            $input_form = [];
            if ($request->has('field_name')) {
                for ($a = 0; $a < count($request->field_name); $a++) {
                    $arr = array();
                    $arr['field_name'] = clean($request->field_name[$a]);
                    $arr['field_level'] = ucwords($request->field_name[$a]);
                    $arr['type'] = $request->type[$a];
                    $arr['field_length'] = $request->field_length[$a];
                    $arr['length_type'] = $request->length_type[$a];
                    $arr['validation'] = $request->validation[$a];
                    $input_form[$arr['field_name']] = $arr;
                }
            }
            $data->services_form = $input_form;
            $data->save();

            Session::flash('success', 'Update Successfully');
            return back();
        } catch (\Exception $exp) {
            return back()->with('error', $exp)->withInput();
        }
    }
}

?>