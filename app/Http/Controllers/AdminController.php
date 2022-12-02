<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Response;
use DB;
use Auth;
use Log;
use Hash;
use Storage;
use File;
use App\Models\User;
use App\Models\Core;
use App\Models\Admin;
class AdminController extends Controller {
	public function missingMethod($params = array()) {
		$notfound = ["error" => "Page not found"];
		return view('errors.404')->with('notfound', $notfound);
	}
	// GET
	public function index() {
		if (Admin::isAdmin()) {
			return view('admin.index');
		}
		return redirect('/');
	}
	public function show($id) {
		if (Admin::isAdmin()) {
			return view('admin.'.$id);
		}
		return redirect('/');
	}
	// POST
	public function store() {
		$ret = "error";
		return Response::make($ret)->header('Content-Type', 'text/html', 'charset', 'utf-8');
	}
	// PUT
	public function update($id) {
		if (Admin::isAdmin()) {
			$r = [];
			if ($id == "users") {
				if (request()->has('uid')) {
					$uid = request()->input('id');
					$d = request()->only(['rank']);
					if ($user = User::find($uid)) {
						User::where('id', $uid)->update($d);
						$r['msg'] = 'User updated';
					}
					$r['id'] = $uid;
				}
			}
			$v = view('admin.'.$id);
			if (isset($r['id'])) {
				$v = $v->with('id',$r['id']);
			}
			if (isset($r['msg'])) {
				$r['type'] = 'success';
				$v = $v->withError(['type' => $r['type'], 'msg' => $r['msg']]);
			}
			return $v;
		}
		return view('welcome');
	}
	// DELETE
	public function destroy($id) {
		if (Admin::isAdmin()) {
			if ($id == "users") {
				$uuid = request()->input('id');
				// [table => SL uuid field]
				$da = [];
				foreach ($da as $dn => $df) {
					DB::table($dn)->where($df, $uuid)->delete();
				}
				User::where('id', $uuid)->delete();
			}
			return view('admin.'.$id);
		}
		return view('welcome');
	}
}
