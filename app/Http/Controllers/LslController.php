<?php
namespace App\Http\Controllers;
use App\Models\Grids;
use App\Models\RESTful;
use App\Http\Controllers\Controller;
class LslController extends Controller {
	public function missingMethod($params = array()) {
		return response()->json(['msg' => $params[0].' not found'], 404)->header('Content-Type', 'application/json')->header('charset', 'utf-8');
	}
	public function index() {
		return response()->json(["error" => "GET method not coded!"], 404)->header('Content-Type', 'application/json')->header('charset', 'utf-8');
	}
	// GET
	public function show($id) {
		return response()->json(["error" => "GET method not coded!"], 404)->header('Content-Type', 'application/json')->header('charset', 'utf-8');
	}
	// POST
	public function store() {
		return response()->json(["error" => "POST method not coded!"], 404)->header('Content-Type', 'application/json')->header('charset', 'utf-8');
	}
	// PUT
	public function update($id, $p = '') {
		if (Grids::securitycheck()) {
			$ret = RESTful::processlsl($id, $p);
			return response()->json($ret, 200)
			->header('Content-Type', 'application/json')
			->header('charset', 'utf-8');
	    }else{
	    	return response()->json(["error" => "SL ONLY!"], 404)
	    	->header('Content-Type', 'application/json')
	    	->header('charset', 'utf-8');
	    }
	}
}
