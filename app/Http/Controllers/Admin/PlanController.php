<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageTime;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function workIndex(){
        $works = Plan::all();
        $page_title = "Plan";
        return view('admin.plan.index',compact('works','page_title'));
    }

    public function workCreate(){
        $page_title = "Create Plan";
        $times = ManageTime::get();
        return view('admin.plan.create', compact('page_title','times'));
    }

    public function workStore(Request $request){
        try{
            if ($request->return_time_status == 1){
                $request->validate([
                    'name' => 'required|max:255',
                    'return_time_status' => 'required',
                    'min_amount' => 'required|numeric|min:0',
                    'max_amount' => 'required|numeric|min:0',
                    'action' => 'required|numeric|min:0',
                    'percent' => 'required|numeric|min:0',
                    'period' => 'required|numeric|min:0',
                    'capital_back_status' => 'required'
                ]);

                if ($request->min_amount >= $request->max_amount){
                    return back()->withErrors(['Minimum amount must be lower than maximum amount']);
                }
                Plan::create([
                    'name' => $request->name,
                    'return_time_status' => 1,
                    'capital_back_status' => $request->capital_back_status,
                    'min_amount' => $request->min_amount,
                    'max_amount' => $request->max_amount,
                    'percent' => $request->percent,
                    'period' => $request->period,
                    'action' => $request->action,
                ]);
                return back()->with('success','ROI Invest Plan Create Successfully');

            }elseif ($request->return_time_status == 2){
                $request->validate([
                    'name' => 'required|max:255',
                    'return_time_status' => 'required',
                    'min_amount' => 'required|numeric|min:0',
                    'max_amount' => 'required|numeric|min:0',
                    'action' => 'required|numeric|min:0',
                    'percent' => 'required|numeric|min:0',
                    'period' => 'required|numeric|min:0',
                    'capital_back_status' => 'required'
                ]);

                if ($request->min_amount >= $request->max_amount){
                    return back()->withErrors(['Minimum amount must be lower than maximum amount']);
                }
                Plan::create([
                    'name' => $request->name,
                    'return_time_status' => 2,
                    'capital_back_status' => $request->capital_back_status,
                    'min_amount' => $request->min_amount,
                    'max_amount' => $request->max_amount,
                    'percent' => $request->percent,
                    'period' => $request->period,
                    'action' => $request->action,
                ]);
                return back()->with('success','Crypto Invest Plan Create Successfully');

            }elseif($request->return_time_status == 0){
                $request->validate([
                    'name' => 'required|max:255',
                    'return_time_status' => 'required',
                    'fixed_amount' => 'required|numeric|min:0',
                    'percent' => 'required|numeric|min:0',
                    'period' => 'required|numeric|min:0',
                    'capital_back_status' => 'required'
                ]);

                Plan::create([
                    'name' => $request->name,
                    'return_time_status' => 0,
                    'capital_back_status' => $request->capital_back_status,
                    'fixed_amount' => $request->fixed_amount,
                    'percent' => $request->percent,
                    'period' => $request->period,
                ]);
                return back()->with('success','Fixed Invest Plan Create Successfully');
            }else{
                return back()->withErrors(['Return time status not matched']);
            }
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function workEdit(Plan $workArea){
        $page_title = "Edit Plan";
        $times = ManageTime::get();
        return view('admin.plan.edit',compact('workArea','page_title','times'));
    }

    public function workUpdate(Request $request, Plan $workArea){
        try{
            if ($request->return_time_status == 1){
                $request->validate([
                    'name' => 'required|max:255',
                    'return_time_status' => 'required',
                    'min_amount' => 'required|numeric|min:0',
                    'max_amount' => 'required|numeric|min:0',
                    'action' => 'required|numeric|min:0',
                    'percent' => 'required|numeric|min:0',
                    'period' => 'required|numeric|min:0',
                    'capital_back_status' => 'required'
                ]);

                if ($request->min_amount >= $request->max_amount){
                    return back()->withErrors(['Minimum amount must be lower than maximum amount']);
                }
                $workArea->update([
                    'name' => $request->name,
                    'return_time_status' => 1,
                    'capital_back_status' => $request->capital_back_status,
                    'min_amount' => $request->min_amount,
                    'max_amount' => $request->max_amount,
                    'percent' => $request->percent,
                    'period' => $request->period,
                    'action' => $request->action,
                    'fixed_amount' => null,
                ]);
                return back()->with('success','ROI Invest Plan updated Successfully');

            }elseif ($request->return_time_status == 2){
                $request->validate([
                    'name' => 'required|max:255',
                    'return_time_status' => 'required',
                    'min_amount' => 'required|numeric|min:0',
                    'max_amount' => 'required|numeric|min:0',
                    'action' => 'required|numeric|min:0',
                    'percent' => 'required|numeric|min:0',
                    'period' => 'required|numeric|min:0',
                    'capital_back_status' => 'required'
                ]);

                if ($request->min_amount >= $request->max_amount){
                    return back()->withErrors(['Minimum amount must be lower than maximum amount']);
                }
                $workArea->update([
                    'name' => $request->name,
                    'return_time_status' => 2,
                    'capital_back_status' => $request->capital_back_status,
                    'min_amount' => $request->min_amount,
                    'max_amount' => $request->max_amount,
                    'percent' => $request->percent,
                    'period' => $request->period,
                    'action' => $request->action,
                    'fixed_amount' => null,
                ]);
                return back()->with('success','Crypto Invest Plan updated Successfully');

            }elseif($request->return_time_status == 0){
                $request->validate([
                    'name' => 'required|max:255',
                    'return_time_status' => 'required',
                    'fixed_amount' => 'required|numeric|min:0',
                    'percent' => 'required|numeric|min:0',
                    'period' => 'required|numeric|min:0',
                    'capital_back_status' => 'required'
                ]);

                $workArea->update([
                    'name' => $request->name,
                    'min_amount' => null,
                    'max_amount' => null,
                    'return_time_status' => 0,
                    'capital_back_status' => $request->capital_back_status,
                    'fixed_amount' => $request->fixed_amount,
                    'percent' => $request->percent,
                    'action' => null,
                    'period' => $request->period,
                ]);
                return back()->with('success','Fixed Invest Plan updated Successfully');
            }else{
                return back()->withErrors(['Return time status not matched']);
            }
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function workDelete(Plan $workArea){
        try{
            $workArea->delete();
            return back()->with('success','Delete Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function scheduleIndex(){
        $data['page_title'] = "Manage Schedule";
        $data['manageTime'] = ManageTime::all();
        return view('admin.plan.schedule', $data);
    }

    public function storeSchedule(Request $request){

        $request->validate([
            'name' => 'required',
            'time' => 'required',
        ]);

        try{
            ManageTime::create([
                'name' => $request->name,
                'time' => $request->time,
            ]);
            return back()->with('success','Create Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function updateSchedule(Request $request, $id){

        $request->validate([
            'name' => 'required',
            'time' => 'required',
        ]);
        try{
            $manageTime = ManageTime::findOrFail($id);
            $manageTime->update([
                'name' => $request->name,
                'time' => $request->time,
            ]);
            return back()->with('success','Update Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }
}

?>