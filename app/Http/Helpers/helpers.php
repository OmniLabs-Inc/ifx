<?php

use App\Models\EmailTemplate;
use App\Models\PaymentGatway;
use App\Models\Transaction;
use App\Models\General;
use App\Models\Referral;
use App\Models\InvestLog;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\User;
use \Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd as Image;


function gs()
{
    $general = General::first();
    return $general;
}

function systemDetails()
{
    $system['name']          = 'matrixlab';
    $system['version']       = '2.0';
    $system['build_version'] = '4.3.4';
    return $system;
}

function verificationCode($length)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = (int) ($min - 1).'9';
    return random_int($min,$max);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function template($asset = false)
{
    $activeTheme = config('basic.theme');
    if ($asset) return 'public/images/themes/' . $activeTheme . '/';
    return 'theme.' . $activeTheme . '.';
}


function getFile($image, $clean = '')
{
    return file_exists($image) && is_file($image) ? asset($image) . $clean : asset(config('location.default'));
}

function removeFile($path)
{
    return file_exists($path) && is_file($path) ? @unlink($path) : false;
}

function slug($title)
{
    return \Illuminate\Support\Str::slug($title);
}

function getAmount($amount, $length = 0)
{
    if (0 < $length) {
        return number_format($amount + 0, $length);
    }
    return $amount + 0;
}

function subroTitle($string)
{
    return Str::title(str_replace('-', ' ', $string));
}

function createTransaction($message,$amount,$oldBalance,$newBalance,$status, $userID = null){
    $transId = substr(rand(0000,9999).time(),6);
    if (!is_null($userID)){
        $me = $userID;
    }else{
        $me = auth()->id();
    }

    return Transaction::create([
       'user_id' =>$me,
       'trans_id' =>$transId,
       'description' =>$message,
       'amount' =>$amount,
       'old_bal' =>$oldBalance,
       'new_bal' =>$newBalance,
       'status' =>$status,
    ]);
}

function split_name($name) {
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
    return array($first_name, $last_name);
}

function uploadImage($image,$location,$name = null,$extension = null,$resize = []){
    try{
        if ($name){
            $replaceName = $name;
        }else{
            $replaceName = uniqid().rand(111,999);
        }
        if ($extension){
            $filename = $replaceName.'.'.$extension;
        }else{
            $filename = $replaceName.'.'.$image->getClientOriginalExtension();
        }
        //$destination = 'public/'.$location.'/'.$filename;
        $destination = $location.'/'.$filename;
        // create image manager with desired driver
        $manager = new ImageManager(
            new Intervention\Image\Drivers\Gd\Driver()
        );
        $img = $manager->read($image);
        if (count($resize) > 0){
            $img->resize($resize[0],$resize[1]);
        }
       // dd($destination);
        $img->save($destination);
        return $filename;
    }catch (Exception $e){
        return $e->getMessage();
    }
}

function shortCodeReplacer($shortCode, $replace_with, $template_string)
{
    return str_replace($shortCode, $replace_with, $template_string);
}


function urlPath($routeName, $routeParam = null)
{
    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }
    $basePath = route('home');
    $path = str_replace($basePath, '', $url);
    return $path;
}

if (! function_exists('can_access'))
{

    function can_access($permission)
    {
        $permission_arr = explode('|',$permission);
        $group_permission= auth()->guard('admin')->user()->group->permissions();

        foreach ($permission_arr as $item){

            if($get_single_per = $group_permission->where('name',$item)->first()){
                if(!$get_single_per['value']){
                    return false;
                }
            }else{
                return false;;
            }
        }

        return true;
    }
}


function Replace($data) {
    $data = str_replace("'", "", $data);
    $data = str_replace("!", "", $data);
    $data = str_replace("@", "", $data);
    $data = str_replace("#", "", $data);
    $data = str_replace("$", "", $data);
    $data = str_replace("%", "", $data);
    $data = str_replace("^", "", $data);
    $data = str_replace("&", "", $data);
    $data = str_replace("*", "", $data);
    $data = str_replace("(", "", $data);
    $data = str_replace(")", "", $data);
    $data = str_replace("+", "", $data);
    $data = str_replace("=", "", $data);
    $data = str_replace(",", "", $data);
    $data = str_replace(":", "", $data);
    $data = str_replace(";", "", $data);
    $data = str_replace("|", "", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace('"', "", $data);
    $data = str_replace("?", "", $data);
    $data = str_replace("  ", "_", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace(".", "-", $data);
    $data = strtolower(str_replace("  ", "-", $data));
    $data = strtolower(str_replace(" ", "-", $data));
    $data = strtolower(str_replace(" ", "-", $data));
    $data = strtolower(str_replace("__", "-", $data));
    return str_replace("_", "-", $data);
}

function short_text($data,$length){
    $first_part = explode(" ",$data);
    $main_part = strip_tags(implode(' ',array_splice($first_part,0, $length)));
    return $main_part ."...." ;
}

function dateTime($date, $format = 'd M, Y h:i A')
{
    return date($format, strtotime($date));
}

function dateSorting($arr)
{
    usort($arr, "dateSort");
    return $arr;
}

function levelCommision($id, $amount){
    $usr = $id;
    $i = 1;
    $level = Referral::count();
    while($usr!="" || $usr!="0" || $i<$level ) {
      $me = User::find($usr);
      $user= User::find($me->ref_id);
        if($user == "") {
            break;
        }
        $comission = Referral::where('id',$i)->first();
        $com = ($amount * $comission->percentage)/100;
        $new_bal = $user->balance +$com;
        $user->balance = $new_bal;
        createTransaction('Congratulation, You Got '.$i.' Level Referral Commission from '.$user->name, $com,$user->balance,$new_bal,5,$user->id);
        $user->save();
        $general = General::first();
        $shortCodes = [
            'amount' => $amount,
            'post_balance' => $new_bal,
            'level' => $i,
            'currency' => $general->currency,
        ];
        @send_email($user, "REFERRAL_COMMISSION", $shortCodes);
        $usr = $user->id;
        $i++;
    }
    return 0;
}


function clean($string) {
    $string = str_replace(' ', '_', $string);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

function curlPostRequestWithHeaders($url, $headers, $postParam = [])
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParam));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function curlGetRequest($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function getIpInfo()
{
    $ip = $_SERVER["REMOTE_ADDR"];

    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }


    $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);


    $country = @$xml->geoplugin_countryName;
    $city = @$xml->geoplugin_city;
    $area = @$xml->geoplugin_areaCode;
    $code = @$xml->geoplugin_countryCode;
    $long = @$xml->geoplugin_longitude;
    $lat = @$xml->geoplugin_latitude;

    $data['country'] = $country;
    $data['city'] = $city;
    $data['area'] = $area;
    $data['code'] = $code;
    $data['long'] = $long;
    $data['lat'] = $lat;
    $data['ip'] = request()->ip();
    $data['time'] = date('d-m-Y h:i:s A');

    return $data;
}

function osBrowser(){
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $osPlatform = "Unknown OS Platform";
    $osArray = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($osArray as $subex => $value) {
        if (preg_match($subex, $userAgent)) {
            $osPlatform = $value;
        }
    }
    $browser = "Unknown Browser";
    $browserArray = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );
    foreach ($browserArray as $subex => $value) {
        if (preg_match($subex, $userAgent)) {
            $browser = $value;
        }
    }

    $data['os_platform'] = $osPlatform;
    $data['browser'] = $browser;

    return $data;
}

function getTrx($length = 6)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getPaginate($paginate = 20)
{
    return $paginate;
}


function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false)
{
    $separator = '';
    if ($separate) {
        $separator = ',';
    }
    $printAmount = number_format($amount, $decimal, '.', $separator);
    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        } else {
            $printAmount = rtrim($printAmount, '0');
        }
    }
    return $printAmount;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}


function showDateTime($date, $format = 'Y-m-d h:i A')
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}
