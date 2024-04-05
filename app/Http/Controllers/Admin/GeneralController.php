<?php

namespace App\Http\Controllers\Admin;

use App\Models\General;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class GeneralController extends Controller
{
    public function bannerIndex(){
        $page_title = "Banner";
        return view('admin.general.banner', compact('page_title'));
    }

    public function aboutIndex(){
        $page_title = "About";
        return view('admin.general.about', compact('page_title'));
    }

    public function googleToolsIndex(){
        $page_title = "Google Tools";
        return view('admin.general.google_ana', compact('page_title'));
    }

    public function icoIndex(){
        $page_title = "ICO Settings";
        return view('admin.general.ico', compact('page_title'));
    }

    public function logoIcon(){
        $page_title = "Logo & Icon";
        return view('admin.general.logo-icon', compact('page_title'));
    }

    public function generalStore(Request $request)
    {
        $gnl = General::first();

        try{
            foreach ($request->all() as $key => $file){
                if ($key != '_token'){
                    $textInputFiledName[] = $key;
                }
            }
            if ($request->file()){
                foreach ($request->file() as $key => $file){
                    if ($key != '_token'){
                        $fileInputFiledName[] = $key;
                    }
                }
            }else{
                $fileInputFiledName = array();
            }

            foreach ($request->except($fileInputFiledName) as $key => $data){
                if ($key != '_token'){
                    $gnl->$key = $data;
                    $gnl->update();
                }
            }

             if($request->timezone){
                 $timezoneFile = config_path('timezone.php');
                 $content = '<?php $timezone = '.$request->timezone.' ?>';
                 file_put_contents($timezoneFile, $content);
             }

            if ($request->hasFile('banner_bg_image')){
                $request->validate([
                    'banner_bg_image' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:2024'
                ]);
                uploadImage($request->file('banner_bg_image'),'images/banner','bg','png');
            }

            if ($request->hasFile('banner_front_image')){
                $request->validate([
                    'banner_front_image' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:2024'
                ]);
                uploadImage($request->file('banner_front_image'),'images/banner','front','png');
            }

            if ($request->hasFile('single_about1_icon')){
                $request->validate([
                    'single_about1_icon' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:2024'
                ]);
                uploadImage($request->file('single_about1_icon'),'images/about','one','png');
            }

            if ($request->hasFile('single_about2_icon')){
                $request->validate([
                    'single_about2_icon' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:2024'
                ]);
                uploadImage($request->file('single_about2_icon'),'images/about','two','png');
            }

            if ($request->hasFile('single_about_img')){
                $request->validate([
                    'single_about_img' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:2024'
                ]);
                uploadImage($request->file('single_about_img'),'images/about','about_right_img','png');
            }

            if ($request->hasFile('logo')){
                $request->validate([
                    'logo' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:2024'
                ]);
                

                uploadImage($request->file('logo'),'images/logo','logo','png');
            }

            if ($request->hasFile('footer_logo')){
                $request->validate([
                    'footer_logo' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:2024'
                ]);
                

                uploadImage($request->file('footer_logo'),'images/logo','footer_logo','png');
            }

            if ($request->hasFile('favicon')){
                $request->validate([
                    'favicon' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:2024'
                ]);
                uploadImage($request->file('favicon'),'images/logo','favicon','png');
            }

            if ($request->hasFile('bread_front_image')){
                $request->validate([
                    'bread_front_image' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:2024'
                ]);
                uploadImage($request->file('bread_front_image'),'images/banner','bred','png');
            }
            
            if ($request->hasFile('bread_footer_image')){
                $request->validate([
                    'bread_footer_image' => 'sometimes|mimes:jpeg,png,bmp,gif,svg|max:2024'
                ]);
                uploadImage($request->file('bread_footer_image'),'images/banner','footer','png');
            }
           
            return back()->with('success','Updated Successfully');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function manageTheme()
    {
        $theme = config('theme');
        $page_title = "Manage Theme";
        return view('admin.general.manage_theme',compact('theme', 'page_title'));
    }

    public function activateTheme(Request $request, $name)
    {
        config(['basic.theme' => $name]);

        $fp = fopen(base_path() . '/config/basic.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('basic'), true) . ';');
        fclose($fp);

        $configure = General::firstOrNew();
        $configure->theme = $name;
        $configure->save();

        session()->flash('success', 'Theme Activated Successfully');
        Artisan::call('optimize:clear');
        return back();
    }
}

?>