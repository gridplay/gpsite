<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use Guzzle\Http\EntityBody;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\stream_for;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Exception\ClientException;

use Auth;
use HTML;
use Response;
use Request;
use Config;
use View;
use Mail;
use Cookie;
use DB;
use Cache;
use User;
use Hash;
use Session;
use Storage;
use File;
use Log;

class Core extends Eloquent {
	public static $servers = [0 => 'Main'];
	public function __construct() {
        return true;
	}
	public static function curl($d = []) {
		return self::senddata($d);
	}
	// Core::curlme($method, $url, $data)
	public static function curlme($meth = 'GET', $url = '', $data = []) {
		$a = [];
		if ($data != []) {
			$a = $data;
		}
		$a['type'] = $meth;
		$a['url'] = $url;
		return self::senddata($a);
	}
	/*
	* Send any data to a in world prim
	* Core::senddata(['type' => 'GET', 'url' => 'url', 'body' => 'otherdata'])
	*/
	public static function senddata($data = []) {
		$headers = self::httpheaders($data);
		$send = ['timeout' => 3.14];
		if (!empty($data['auth'])) {
			$send['auth'] = $data['auth'];
		}
		if (!empty($data['body'])) {
			$send['body'] = $data['body'];
		}
		if (!empty($data['query'])) {
			$send['query'] = $data['query'];
		}
		if (!empty($data['json'])) {
			$send['json'] = $data['json'];
		}
		if (!empty($data['form'])) {
			$send['form_params'] = $data['form'];
		}
		if (!empty($data['form_params'])) {
			$send['form_params'] = $data['form_params'];
		}
		if (isset($data['timeout'])) {
            $send['timeout'] = $data['timeout'];
        }
		$client = new Client($headers); // Guzzle
		try {
			$response = $client->request($data['type'], $data['url'], $send);
			$body = $response->getBody();
			if ($response->getStatusCode() == 200) {
				if ($response->getHeaderLine('Content-Type') == "application/json") {
					return json_decode($body->getContents(), true);
				}else{
					return $body->getContents();
				}
			}else{
				return false;
			}
		}catch(\Exception $e) {
			return false;
		}
		return false;
	}
	private static function httpheaders($data = []) {
	    $h = [];
		if (isset($data['heads'])) {
			$h = $data['heads'];
        }
        /* application/x-www-form-urlencoded */
		$h['verify'] = "false";
		$h['content-type'] = 'application/x-www-form-urlencoded';
		if (array_key_exists('json', $data)) {
			$h['content-type'] = 'application/json';
		}
        if (isset($data['content-type'])) {
            $h['content-type'] = $data['content-type'];
        }
		return ['headers' => $h];
	}
	public static function xml($string, $assoc = false) {
		if ($string) {
			$xml = @simplexml_load_string($string);
			if(!$xml) {
				return [];
			}else{
				return json_decode(json_encode($xml), $assoc);
			}
		}else{
			return [];
		}
	}
    public static function make_seed() {
		list($usec, $sec) = explode(' ', microtime());
		return (float) $sec + ((float) $usec * 100000);
	}
	public static function getprice($price) {
		$pricecentage = 0;
		if ($price > 0) {
			$sale = 10;
			if ($sale > 0) {
				$pricecentage = $price / $sale;
			}
		}
		return number_format($pricecentage);
	}
	public static function howlong($ptime) {
		$etime = time() - $ptime;
	    if ($etime < 10) {
	        return 'Just now';
	    }
	    $a = array( 365 * 24 * 60 * 60  =>  'year',
	                 30 * 24 * 60 * 60  =>  'month',
	                      24 * 60 * 60  =>  'day',
	                           60 * 60  =>  'hour',
	                                60  =>  'minute',
	                                 1  =>  'second');
	    $a_plural = array( 'year'   => 'years',
	                       'month'  => 'months',
	                       'day'    => 'days',
	                       'hour'   => 'hours',
	                       'minute' => 'minutes',
	                       'second' => 'seconds');
	    foreach ($a as $secs => $str) {
	        $d = $etime / $secs;
	        if ($d >= 1) {
	            $r = round($d);
	            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
	        }
	    }
	}

	public static function seconds2readable($seconds) {
		return gmdate("H:i:s", $seconds);
	}
	public static function secs2readable($secs) {
		$timeStr = "";
	    $days = "";
	    $hours = "";
	    $minutes = "";
	    if ($secs >= 86400)
	    {
	        $days = floor($secs/86400);
	        $secs = $secs%86400;
	        $timeStr .= $days." day";
	        if ($days > 1)
	        {
	            $timeStr .= "s";
	        }
	        if($secs > 0)
	        {
	            $timeStr .= ", ";
	        }
	    }
	    if($secs >= 3600)
	    {
	        $hours = floor($secs/3600);
	        $secs = $secs%3600;
	        $timeStr .= $hours." hour";
	        if($hours != 1) 
	        {
	            $timeStr .= "s";
	        }
	        if($secs > 0)
	        {
	            $timeStr .= ", ";
	        }
	    }
	    if($secs >= 60)
	    {
	        $minutes = floor($secs/60);
	        $secs = $secs%60;
	        $timeStr .= $minutes." minute";
	        if($minutes != 1)
	        {
	            $timeStr .= "s";
	        }
	        if($secs > 0)
	        {
	            $timeStr .= ", ";
	        }
	    }
	    if ($secs > 0)
	    {
	        $timeStr .= $secs." second";
	        if($secs != 1)
	        {
	            $timeStr .= "s";
	        }
	    }
		return $timeStr;
	}

	public static function time2date($time = "", $dater = 'M d Y g:i.sA') {
		if ($time != "") {
			if (is_numeric($time)) {
			}else{
				$time = strtotime($time);
			}
			if ($time != 0) {
				$d = date($dater, $time);
				if (strpos($d, "484") !== false) {
					$timer = $time / 1000;
					$d = date($dater, $timer);
				}
				return $d;
			}else{
				return "";
			}
		}else{
			return "";
		}
	}
	// this converts readable time to UNIX time
	public static function date2time($date = "") {
		if ($date != "") {
			if (!is_numeric($date)) {
				return strtotime($date);
			}else{
				return "";
			}
		}else{
			return "";
		}
	}

	public static function readabletimestamp($date = null) {
		if (!is_null($date)) {
			if (!is_numeric($date)) {
				return self::time2date(self::date2time($date));
			}else{
				return self::time2date($date);
			}
		}else{
			return self::time2date(time());
		}
	}

	/*
	* Random code generator, mostly for passwords
	*/
	public static function randcode($length, $min = 0) {
		if ($min > 0) {
			$length = rand($min, $length);
		}
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWZYZ';
		return substr(str_shuffle($chars),0,$length);
	}

	// this MAY come in handy in the future
	public static function strpos_array($haystack, $needles) {
	    if (is_array($needles)) {
	        foreach ($needles as $str) {
	            if (is_array($str)) {
	                $pos = self::strpos_array($haystack, $str);
	            } else {
	                $pos = strpos($haystack, $str);
	            }
	            return $pos;
	        }
	    }else{
	        return strpos($haystack, $needles);
	    }
	}

	public static function avg($one, $two, $dby = 2) {
		return ($one + $two) / $dby;
	}

	public static function json_clean_decode($json, $assoc = true, $depth = 9, $options = 0) {
	    $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t](//).*)#", '', $json);
	    $json = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json);
	    if(version_compare(phpversion(), '5.4.0', '>=')) {
	        $json = json_decode($json, $assoc, $depth, $options);
	    } elseif (version_compare(phpversion(), '5.3.0', '>=')) {
	        $json = json_decode($json, $assoc, $depth);
	    } else {
	        $json = json_decode($json, $assoc);
	    }
	    return $json;
	}

	// Core::subs($conf, $weeks)
	/*public static function subs($conf, $weeks) {
		$oneweek = 604800;
		$fee = config('vl.fees');
		$total = $weeks * $fee[$conf];
		$length = $oneweek * $weeks;
		return ['total' => $total, 'length' => $length];
	}*/

	/*
	| easy API to send emails
	| Core::sendemail($toWho, $subj, $msg)
	*/
	public static function sendemail($toWho, $subj, $msg) {
		if ($e = DB::table('unsubscribe')->where('email', $toWho)->first()) {
			return "email address has already unsubscribed";
		}else{
	        Mail::send(['email.html', 'email.text'], ['subj' => $subj, 'addy' => $toWho, 'msg' => $msg], function ($message) use ($toWho, $subj) {
	            $conf = config('mail.from');
	            $message->from($conf['address'], $conf['name']);
	            $message->to($toWho);
	            $message->subject($subj);
	        });
	        return "sent";
	    }
	}

	public static function parseTable($html) {
		preg_match("/<table.*?>.*?<\/[\s]*table>/s", $html, $table_html);
		preg_match_all("/<th.*?>(.*?)<\/[\s]*th>/", $table_html[0], $matches);
		$row_headers = $matches[1];
		preg_match_all("/<tr.*?>(.*?)<\/[\s]*tr>/s", $table_html[0], $matches);
		$table = array();
		foreach($matches[1] as $row_html) {
			preg_match_all("/<td.*?>(.*?)<\/[\s]*td>/", $row_html, $td_matches);
			$row = array();
			for($i=0; $i<count($td_matches[1]); $i++) {
				$td = strip_tags(html_entity_decode($td_matches[1][$i]));
				$row[$row_headers[$i]] = $td;
			}
			if(count($row) > 0) {
			$table[] = $row;
			}
		}
		return $table;
	}

	// Core::getHeaders($h)
	public static function getHeaders($h = "") {
		$r = null;
		if (!empty($h)) {
			if (is_array($h)) {
				foreach ($h as $a) {
					$r[$a] = self::headerswitch($a);
				}
			}else{
				$r = self::headerswitch($h);
			}
		}
		return $r;
	}
	// Core::headerswitch("")
	public static function headerswitch($h = "") {
		$r = null;
		switch ($h) {
			case 'objpos':
				$r = str_replace("(", "", str_replace(")", "", Request::header("X-SecondLife-Local-Position")));
				break;
			case 'objrot':
				$r = str_replace("(", "", str_replace(")", "", Request::header("X-SecondLife-Local-Rotation")));
				break;
			case 'objvel':
				$r = str_replace("(", "", str_replace(")", "", Request::header("X-SecondLife-Local-Velocity")));
				break;
			case 'objkey':
				$r = Request::header("X-SecondLife-Object-Key");
				break;
			case 'objname':
				$r = Request::header("X-SecondLife-Object-Name");
				break;
			case 'ownerkey':
				$r = Request::header("X-SecondLife-Owner-Key");
				break;
			case 'ownername':
				$r = str_replace(" Resident", "", Request::header("X-SecondLife-Owner-Name"));
				break;
			case 'sim':
				list($sim, $coords) = explode(" (", str_replace(")", "", Request::header("X-SecondLife-Region")));
				$r = $sim;
				break;
			case 'simcoords':
				list($sim, $coords) = explode("(", str_replace(")", "", Request::header("X-SecondLife-Region")));
				list($x,$y) = explode(",", $coords);
				$r = ["x" => ($x / 256), "y" => ($y / 256), 'gx' => $x, 'gy' => $y];
				break;
			case 'shard':
				$r = Request::header("X-SecondLife-Shard");
				break;
			case 'user':
				$r = Request::header("User-Agent");
				break;
			default:
				$r = null;
				break;
		}
		return $r;
	}

	public static function array_random($arr, $num = 1) {
	shuffle($arr);
		$r = array();
		for ($i = 0; $i < $num; $i++) {
			$r[] = $arr[$i];
		}
		return $num == 1 ? $r[0] : $r;
	}

	public static function Array2Csv($data, $delimiter = ',', $enclosure = '"') {
		$handle = fopen('php://temp', 'r+');
		foreach ($data as $line) {
			fputcsv($handle, $line, $delimiter, $enclosure);
		}
		rewind($handle);
		while (!feof($handle)) {
			$contents .= fread($handle, 8192);
		}
		fclose($handle);
		return $contents;
	}

	public static function txt2html($filename = "", $sep = "<br>") {
		if (!empty($filename)) {
			if (Storage::exists($filename)) {
				$file = Storage::get($filename);
				if (strpos($filename, ".md") !== false) {
					$html = markdown($file);
				}else{
					$html = str_replace(PHP_EOL, $sep, $file);
				}
				return $html;
			}
		}
		return null;
	}

	public static function randvector() {
		$one = (rand(0,100) / 100);
		$two = (rand(0,100) / 100);
		$tah = (rand(0,100) / 100);
		return "<".$one.",".$two.",".$tah.">";
	}
	// Core::DistanceCalc($x, $y, $tox, $toy)
	public static function DistanceCalc($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
		// convert from degrees to radians
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
		cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
		return $angle * $earthRadius;
	}
}
