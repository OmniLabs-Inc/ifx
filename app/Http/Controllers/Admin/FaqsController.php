<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    public function index()
    {
        $data['page_title'] = "FAQ Section";
        $data['faq'] = Faq::orderBy('id', 'DESC')->get();
        return view('admin.faqs.index', $data);
    }

    public function create(){
        $data['page_title'] = "Create FAQ Section";
        return view('admin.faqs.create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);
        try{
            Faq::create([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);
            return back()->with('success',__('Create Successfully'));
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function edit($id){
        $data['page_title'] = "Edit FAQ Section";
        $data['faq'] = Faq::find($id);
        return view('admin.faqs.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);
        try{
            $faq = Faq::find($id);
            $faq->update([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);
            return back()->with('success',__('Update Successfully'));
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try{
            $faq = Faq::find($id);
            $faq->delete();
            return back()->with('success','Delete Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }
}
