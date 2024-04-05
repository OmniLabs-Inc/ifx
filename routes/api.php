<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
# added rate limiter 3 request per mintue
# Don't need any Token for accessing this token.
Route::group(['middleware' => 'throttle:30,1'], function () {
    Route::post('/register', 'UserController@register');
    Route::post('/login', 'UserController@login');
    Route::post('/send_otp', 'UserController@sendOtp');
    Route::post('/verify_otp', 'UserController@validate_otp');
    Route::post('/verify_email', 'UserController@verifyEmail');
    Route::post('/change_password', 'UserController@fogotPassword');
    Route::post('/resend_otp', 'UserController@resendOtp');
    Route::post('/login_by_admin', 'UserController@login_by_admin');

    Route::post('/start-forgot-password', 'UserController@startForgotPassword');
});


# Need Valid Token for accessing this apis => user.
Route::middleware(['auth:api'])->group(function () {
    Route::post('/changepassword', 'UserController@changepassword');
    Route::post('/changeprofile', 'UserController@changeProfile');

    # Logout user with the authentication i.e. token..
    //Route::delete('/logout', 'UserController@logout');
    //Route::delete('/hardlogout', 'UserController@hard_logout');

    Route::post('/logout', 'UserController@logout');
    Route::post('/hardlogout', 'UserController@hard_logout');



    Route::get('/downline_report', 'UserController@downline_team_report'); // report

    Route::get('/tree_node', 'TreeController@tree_node');
    Route::get('/tree_node_generation', 'TreeController@tree_node_generation');
    Route::get('/s_detail', 'TreeController@getsponser_detail');


    # Calculate Price
    Route::get('/price_', 'StakingController@calculate_price');


    # Deposit Crypto Module
    Route::group(['prefix' => 'deposit'], function () {
        // Route::post('/i', 'DepositController@initiate'); // initiate
        // Route::post('/c', 'DepositController@canceled'); // canceled
        // Route::post('/v', 'DepositController@verification'); // verification
        Route::post('/submit', 'DepositController@submitCryptoDeposit');
        Route::get('/report', 'DepositController@deposit_report'); // report
    });

    # Deposit Crypto Module
    Route::group(['prefix' => 'stake'], function () {
        Route::post('/plan', 'StakingController@stake_now'); // initiate
        Route::post('/eduplan', 'StakingController@buy_education_package');
        Route::get('/report', 'StakingController@stake_report'); // report
        Route::post('/convert', 'StakingController@convert_usdt'); // report
    });

    # Deposit Crypto Module
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/get', 'DashboardController@get'); // initiate
        Route::get('/get-single/{id}', 'DashboardController@getSingle'); // initiate
        Route::get('/reward_available', 'DashboardController@reward_available'); // initiate
        Route::get('/settings', 'DashboardController@getSetting'); // get settings for withdrawal
    });

    # Income Routes Modules
    Route::group(['prefix' => 'income'], function () {
        Route::get('/roi', 'FetchIncomeController@roiIncome');
        Route::get('/direct', 'FetchIncomeController@directIncome');
        Route::get('/wash', 'FetchIncomeController@washoutIncome');
        Route::get('/matching', 'FetchIncomeController@matchingIncome');
        Route::get('/level-bonus', 'FetchIncomeController@levelIncome');
        Route::get('/reward', 'FetchIncomeController@rewardIncome');
        Route::get('/match-history', 'FetchIncomeController@matching_history');
    });

    # Tree Routes Modules
    Route::group(['prefix' => 'tree'], function () {
        Route::get('/binary', 'TreeController@binary');
        // Route::get('/s_detail', 'TreeController@getsponser_detail');
        Route::get('/generation', 'TreeController@generation');
    });

    # Wallet Routes Modules
    Route::group(['prefix' => 'wallet'], function () {
        Route::get('/report', 'UserController@wallet_report');
    });

    # Wallet Routes Modules
    Route::group(['prefix' => 'direct'], function () {
        Route::get('/report', 'UserController@direct_team_report');
    });

    # Internal P2p Module
    Route::group(['prefix' => 'p2p'], function () {
        Route::post('/create', 'P2PController@create');
        Route::post('/redeem', 'P2PController@redeem');
        Route::get('/get', 'P2PController@get');
        Route::get('/get-reedeem', 'P2PController@getReedeem');
    });

    # Deposit Crypto Module
    Route::group(['prefix' => 'withdraw'], function () {
        Route::post('/i', 'WithdrawalController@initiate'); // initiate
        Route::get('/history', 'WithdrawalController@get'); // canceled
    });
});

# Need Valid Token for accessing this apis => admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'admin']], function () {

    # Fund Transfer Module
    Route::prefix('fund')->namespace('Admin')->group(function () {
        Route::post('/transfer', 'FundTransferController@transfer');
        Route::get('/get', 'FundTransferController@get');
    });

    # stake plan by admin
    Route::post('/stake-by-admin', 'StakingController@stakeByAdmin');

    # Dashboard Module
    Route::prefix('dashboard')->namespace('Admin')->group(function () {
        Route::get('/get', 'DashboardController@get');
    });

    # User Module
    Route::prefix('user')->namespace('Admin')->group(function () {
        Route::get('/get', 'UserController@get');
        Route::get('/list', 'UserController@list');
        Route::get('/detail', 'UserController@detail');
        Route::post('/block', 'UserController@block');
        Route::post('/change_password', 'UserController@change_password');
    });

    # Income Module
    Route::prefix('income')->namespace('Admin')->group(function () {
        Route::get('/roi', 'IncomeController@roiIncome');
        Route::get('/direct', 'IncomeController@directIncome');
        Route::get('/wash', 'IncomeController@washoutIncome');
        Route::get('/matching', 'IncomeController@matchingIncome');
        Route::get('/reward', 'IncomeController@rewardIncome');
    });

    # Income Module
    Route::prefix('report')->namespace('Admin')->group(function () {
        Route::get('/deposit', 'ReportController@deposit_report');
        Route::get('/stake', 'ReportController@stake_report');
        Route::get('/p2p', 'ReportController@p2p_report');
    });

    # Set Price Module
    Route::prefix('set')->namespace('Admin')->group(function () {
        Route::post('/alpha_price', 'SettingController@setAfcPrice');
        Route::post('/withdraw/commision', 'SettingController@setWithdrawalFees');
    });

    Route::prefix('withdraw')->namespace('Admin')->group(function () {
        Route::get('/get', 'WithdrawalController@get');
    });
});


# ROI INCOME GENERATE BY CRON JOB
Route::prefix('stake-plan')->namespace('Income')->group(function () {
    Route::post('/expiry', 'ExpiryStakedPlanController@expiry');
});

# ROI INCOME GENERATE BY CRON JOB
Route::prefix('income-generate')->namespace('Income')->group(function () {
    Route::post('/roi-level', 'RoiIncomeController@generateIncome'); # ROI LEVEL INCOME (DAILY BASED)
    Route::post('/match', 'MatchingIncomeController@generateIncome'); # MATCHING INCOME (DAILY CLOSING )
    Route::post('/royal', 'RewardIncomeController@generateIncome'); # REWARD INCOME (DAILY CLOSING)
    // Route::post('/setup', 'MatchingIncomeController@setupBusinessIncome');
    Route::get('level-income','LevelIncomeController@generateIncome'); // LEVEL INCOME (MONTHLY)
    Route::get('remove-duplicate','LevelIncomeController@removeDuplicateIncome');
    Route::get('check-qualify','LevelIncomeController@updateUsersIncomeStatus'); // CRON JOB HOURLY

    Route::post('/test', 'MatchingIncomeController@test');
    Route::get('/collect-earnings', 'RoiIncomeController@collectEarningsForWithdrawal');
});

