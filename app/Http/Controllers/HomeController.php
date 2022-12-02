<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
class HomeController extends Controller {
	public function missingMethod($params = array()) {
		return view('errors.404');
	}
	public function index() {
		return view('welcome');
	}
	public function show($id) {
		if (empty($id)) {
			return view('welcome');
		}else{
			if (view()->exists($id)) {
				return view($id);
			}else{
				return view('welcome');
			}
		}
	}
	public function showPage($id = "") {
		if (empty($id)) {
			return view('welcome');
		}else{
			if (view()->exists($id)) {
				return view($id);
			}else{
				return view('welcome');
			}
		}
	}
}
