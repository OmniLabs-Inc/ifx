<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function workIndex(){
        $works = Service::all();
        $page_title = "Services";
        return view('admin.service.index',compact('works', 'page_title'));
    }

    public function workCreate(){
        $page_title = "Create Services";
        return view('admin.service.create', compact('page_title'));
    }

    public function workStore(Request $request){
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'icon' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:1024'
        ]);
        try{
            $fileName = uploadImage($request->file('icon'),'images/service');
            Service::create([
                'icon' => $fileName,
                'title' => $request->title,
                'description' => $request->description,
            ]);
            return back()->with('success','Create Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function workEdit(Service $workArea){
        $page_title = "Edit Services";
        return view('admin.service.edit',compact('workArea', 'page_title'));
    }

    public function workUpdate(Request $request, Service $workArea){
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'icon' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:1024'
        ]);
        try{
            if ($request->file('icon')){
                @unlink('public/images/service/'.$workArea->icon);
                $fileName = uploadImage($request->file('icon'),'images/service');
            }else{
                $fileName = $workArea->icon;
            }
            $workArea->update([
                'icon' => $fileName,
                'title' => $request->title,
                'description' => $request->description,
            ]);
            return back()->with('success','Update Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function workDelete(Service $workArea){
        try{
            @unlink('public/images/service/'.$workArea->icon);
            $workArea->delete();
            return back()->with('success','Delete Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }
}

?>