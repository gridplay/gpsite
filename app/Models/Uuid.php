<?php
namespace App\Models;
use DB;
use Request;
use Str;
class Uuid extends Core {
	public static function generate($gen = null) {
		return Str::uuid();
	}
	public static function isUuid($uuid = "") : bool {
		return Str::isUuid($uuid);
	}
}
