<?php
namespace App\Models;
use Log;
use App\Models\Core;
use App\Models\Grids;
class RESTful extends Core {
	public static function processlsl($id = '', $p = '') {
		if (Grids::securitycheck()) {
			$args = [
				//'uri' => 'Class',
			];
			if (array_key_exists($id, $args)) {
				$classcall = "App\\Models\\".$args[$id];
				if (empty($p)) {
					return $classcall::process();
				}else{
					return $classcall::process($p);
				}
			}
			if ($id == "test") {
				return ["uri" => "test", "p" => $p, "d" => request()->all()];
			}
		}
		return ['error' => 'Invalid grid'];
	}
}
