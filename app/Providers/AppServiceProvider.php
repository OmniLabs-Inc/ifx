<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Pranto\MultiLanguage\Models\Language;
use App\Models\General;
use App\Models\Menu;
use App\Models\News;
use App\Models\Notification;
use App\Models\Support;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Schema::defaultStringLength(191);

        view()->share('general', General::first());
        //view()->share('lang', Language::get());
        view()->share('check_count', Support::where('status', 1)->get());

        view()->composer('admin.layouts.partials.nav', function ($view) {
            $view->with([
                'adminNotifications' => Notification::where('read_status',0)->with('user')->orderBy('id','desc')->get(),
            ]);
        });

        //$data['theme'] = template();
        //$data['themeTrue'] = template(true);

        //View::share($data);
    }
}
