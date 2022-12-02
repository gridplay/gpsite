<?php
namespace App\Models;
use Cache;
use Log;
use Storage;
use Mail;
use Auth;
use GridPlay;
use App\Models\User;
class Grids extends Core {
	public static $nullkey = "00000000-0000-0000-0000-000000000000";
	// Grids::securitycheck()
	public static function securitycheck() {
		$h = parent::getHeaders(['user', 'shard']);
		$strone = strpos($h['user'], "Second-Life-LSL");
		if ($strone !== false && $h['shard'] == "Production") {
			return true;
		}
		return false;
	}

	// Grids::IMuser($uuid, $msg)
	public static function IMuser($uuid, $msg) {
		if (GridPlay::sendIM($uuid, $msg)) {
			return true;
		}else{
			return false;
		}
	}
	// Grids::key2name($uuid)
	public static function key2name($u) {
		if ($user = User::where('uuid', $u)->first()) {
			return $user->name;
		}else{
			if ($name = GridPlay::Key2Name($u)) {
				return $name;
			}
			return null;
		}
	}
	// Grids::name2key($n)
	public static function name2key($n) {
		if (strpos($n, ".") !== false) {
			list($f,$l) = explode(".", $n);
			if (strtolower($l) == "resident") {
				$name = $f;
			}else{
				$name = str_replace('.',' ',$n);
			}
		}else{
			$name = $n;
		}
		if ($user = User::where('name', $name)->first()) {
			return $user->uuid;
		}else{
			if ($key = GridPlay::Name2Key($name)) {
				return $key;
			}
			return self::$nullkey;
		}
	}
}
