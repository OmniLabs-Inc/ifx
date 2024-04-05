<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function workIndex(){
        $works = Menu::all();
        $page_title = "Menus";
        return view('admin.menu.index',compact('works','page_title'));
    }

    public function workCreate(){
        $page_title = "Create Menus";
        return view('admin.menu.create', compact('page_title'));
    }

    public function workStore(Request $request){
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
        try{
            Menu::create([
                'title' => $request->title,
                'slug' => Str::slug($request['title'], '-'),
                'description' => $request->description,
            ]);
            return back()->with('success','Create Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function workEdit(Menu $workArea){
        $page_title = "Edit Menus";
        return view('admin.menu.edit',compact('workArea','page_title'));
    }

    public function workUpdate(Request $request, Menu $workArea){
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
        try{
            $workArea->update([
                'title' => $request->title,
                'slug' => Str::slug($request['title'], '-'),
                'description' => $request->description,
            ]);
            return back()->with('success','Update Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function workDelete(Menu $workArea){
        try{
            $workArea->delete();
            return back()->with('success','Delete Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }
}

?>