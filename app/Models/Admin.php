<?php
namespace App\Models;
use Auth;
use Log;
use App\Models\User;
class Admin extends Core {
    public static $menu = [
        'users' => 'Users',
    ];
    public static function isAdmin($id = null) {
        if (is_null($id) && Auth::check()) {
        	$id = Auth::id();
        }
        if (!is_null($id)) {
            if ($user = User::find($id)) {
                if ($user->isadmin == 1) {
                    return true;
                }
            }
        }
        return false;
    }
    public static function ValidCaptcha() {
        $f = config('site.recaptcha');
        if (request()->has('g-recaptcha-response')) {
            $c = request()->input('g-recaptcha-response');
            $d = ['secret' => $f['secret'], 'response' => $c];
            $a = ["type" => "POST", "url" => $f['url'], "form" => $d];
            $r = parent::senddata($a);
            $r = json_decode($r, true);
            if (array_key_exists('success',$r)) {
                if ($r['success'] == true) {
                    return true;
                }
            }
        }
        return false;
    }
}
