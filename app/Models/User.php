<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Auth;
use Storage;
use Request;
use Image;
use Hash;
use File;
use Log;
use App\Models\Core;
use App\Models\Uuids;
class User extends Authenticatable {
    use HasFactory, Notifiable, Uuids;
    protected $fillable = ['id', 'name', 'gpid', 'uuid'];
    protected $hidden = ['remember_token'];
    protected $table = 'users';
    public $incrementing = false;
    public static function parseName($name) {
        $name = strtolower($name);
        $name = str_replace(".", " ", $name);
        $name = str_replace(" resident", "", $name);
        return $name;
    }
    public static function findbyuuid($uuid) {
        if ($u = self::where('uuid', $uuid)->first()) {
            return $u;
        }
        return null;
    }
    public static function findbyname($name) {
        $n = strtolower($name);
        $n = str_replace(".", " ", $n);
        $n = str_replace(" resident", "", $n);
        if ($u = self::where('name', $n)->first()) {
            return $u;
        }
        return null;
    }
    // User::ucheck($uuid)
    public static function ucheck($uuid) {
        if ($u = self::where('uuid', $uuid)->first()) {
            return true;
        }
        return false;
    }
    public static function id2name($id) {
        if ($u = self::find($id)) {
            return $u->name;
        }else{
            return null;
        }
    }
    public static function id2uuid($id) {
        if ($u = self::find($id)) {
            return $u->slid;
        }else{
            return null;
        }
    }
}
