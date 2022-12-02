<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Socialite;
use Log;
use App\Models\User;
class AuthController extends Controller {
    public function index() {
        return view('welcome');
    }
    public function show($id) {
        if ($id == "logout") {
            Auth::logout();
            return view('welcome');
        }
        if ($id == "login") {
            return Socialite::driver('gplogin')->redirect();
        }
    }
    public function login() {
    }
    public function callback() {
        $user = Socialite::driver('gplogin')->user();
        if ($u = User::where('gpid', $user->id)->first()) {
            Auth::login($u, true);
        }else{
            $u = User::create(['id' => uuid(),
                        'name' => $user->name,
                        'gpid' => $user->id,
                        'uuid' => $user->uuid]);
            Auth::login($u, true);
        }
        return redirect('/');
    }
}
