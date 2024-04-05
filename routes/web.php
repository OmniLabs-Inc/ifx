<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('clean',function(){
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
 });

Route::get('/', function () {
    return view('welcome');
});



Route::group(['prefix' => 'admin','namespace'=> 'Admin'], function () {
    Route::get('/', 'AdminLoginController@showAdminLoginForm');
    Route::post('/', 'AdminLoginController@adminLogin')->name('admin.login');
    Route::get('adminlogin', 'AdminLoginController@showAdminLoginForm')->name('login');

    Route::group(['middleware' => ['auth:admin']], function () {
        Route::get('dashboard', 'AdminController@adminIndex')->name('admin.home');


            Route::post('general', 'GeneralController@generalStore')->name('general.store');
            Route::get('settings', 'AdminController@gnlSetting')->name('admin.gnl.set');
            Route::get('select/template', 'AdminController@selTemplate')->name('admin.gnl.template');


        Route::group(['middleware'=>'permission:language'],function (){
            Route::get('/language/manager', 'LanguageController@langManage')->name('language-manage');
            Route::post('/language/manager', 'LanguageController@langStore')->name('language-manage-store');
            Route::delete('language-manage/{id}', 'LanguageController@langDel')->name('language-manage-del');
            Route::get('language-key/{id}', 'LanguageController@langEdit')->name('language-key');
            Route::put('key-update/{id}', 'LanguageController@langUpdate')->name('key-update');
            Route::post('language-manage-update/{id}', 'LanguageController@langUpdatepp')->name('language-manage-update');
            Route::post('language-import', 'LanguageController@langImport')->name('import_lang');
        });

        Route::group(['middleware'=>'permission:theme'],function (){
            Route::get('/manage/theme', 'GeneralController@manageTheme')->name('manage.theme');
            Route::put('/activate/theme/{name}', 'GeneralController@activateTheme')->name('activate.themeUpdate');
        });


        Route::get('notifications','NotificationController@notifications')->name('admin.notifications');
        Route::get('notification/read/{id}','NotificationController@notificationRead')->name('admin.notification.read');
        Route::get('notifications/read-all','NotificationController@readAll')->name('admin.notifications.readAll');

            Route::get('banner', 'GeneralController@bannerIndex')->name('banner.index');
            Route::get('logo-icon', 'GeneralController@logoIcon')->name('logo-icon.index');

            Route::get('withdraw-method', 'WithDrawMethodController@indexWithdraw')->name('add.withdraw.method');
            Route::post('withdraw-store', 'WithDrawMethodController@storeWithdraw')->name('store.withdraw.method');
            Route::put('withdraw/update/{id}', 'WithDrawMethodController@updateWithdraw')->name('update.withdraw.method');

            Route::get('withdraw/requests', 'WithDrawMethodController@requestWithdraw')->name('withdraw.request.index');
            Route::get('withdraw/details/{id}', 'WithDrawMethodController@detailWithdraw')->name('withdraw.detail.user');
            Route::post('withdraw/update/{id}', 'WithDrawMethodController@repondWithdraw')->name('withdraw.process');

            Route::get('withdraw/log', 'WithDrawMethodController@showWithdrawLog')->name('withdraw.viewlog.admin');

            Route::get('user-group','ManageUserController@index')->name('admin.user_group');
            Route::get('group/create','ManageUserController@create')->name('admin.group.create');
            Route::post('group/store','ManageUserController@store')->name('admin.group.store');
            Route::get('group/{id}/edit','ManageUserController@edit')->name('admin.group.edit');
            Route::post('group/{id}/update','ManageUserController@update')->name('admin.group.update');
            Route::get('user','ManageUserController@getUser')->name('admin.user');
            Route::post('user/store','ManageUserController@userStore')->name('admin.user.store');
            Route::post('user/update/{id}','ManageUserController@userUpdate')->name('admin.user.update');

            Route::get('users', 'AdminController@usersIndex')->name('user.manage');
            Route::get('users/detail/{id}', 'AdminController@indexUserDetail')->name('user.view');
            Route::GET('user/search', 'AdminController@userSearch')->name('username.search');
            Route::GET('user/search/email', 'AdminController@userSearchEmail')->name('email.search');
            Route::post('users/amount/{id}', 'AdminController@indexBalanceUpdate')->name('user.balance.update');
            Route::get('users/send/mail/{id}', 'AdminController@userSendMail')->name('user.mail.send');
            Route::post('send/mail/{id}', 'AdminController@userSendMailUser')->name('send.mail.user');
            Route::get('users/balance/{id}', 'AdminController@indexUserBalance')->name('add.subs.index');
            Route::put('users/update/{id}', 'AdminController@userUpdate')->name('user.detail.update');

            Route::post('active/wallet/users/update/{id}', 'AdminController@activeUserUpdate')->name('adm-active-wallet.update');
            Route::post('reject/wallet/users/update/{id}', 'AdminController@rejectUserUpdate')->name('adm-reject-wallet.update');
            Route::post('users/password/reset/{id}', 'AdminController@userPasswordReset')->name('admin-password-reset.update');

            Route::get('active-user', 'AdminController@usersActiveIndex')->name('active.user.manage');

            Route::get('ban-user', 'AdminController@usersBanndedIndex')->name('ban.user.manage');

            Route::get('deposit/pending','DepositController@pending')->name('admin.deposit.pending');
            Route::get('deposit/showReceipt', 'DepositController@showReceipt')->name('admin.deposit.showReceipt');
            Route::get('deposit/depositLog','AdminController@depositLog')->name('admin.deposit.depositLog');
            Route::get('deposit/rejectedRequests','DepositController@rejectedRequests')->name('admin.deposit.rejectedRequests');

            Route::get('transactions', 'AdminController@transactionIndex')->name('transaction.log.admin');
            Route::get('search-transaction/', 'AdminController@searchResult')->name('search.trans.admin');


        Route::get('profile', 'AdminController@changePass')->name('admin.changePass');
        Route::post('profile/updatePassword', 'AdminController@updatePassword')->name('admin.updatePassword');

        Route::get('logout', 'AdminController@adminLogout')->name('admin.logout');
    });
});


// Route::get('/binary', UserController@binaryBlade);


// Route::get('binary/{id}', [UserController::class, 'binaryblade'])->name('binaryblade');
Route::get('generation/{id}', [UserController::class, 'generationblade'])->name('generationblade');
