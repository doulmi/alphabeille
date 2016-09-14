<?php

namespace App;

use App\ActionType;
use Auth;
use Illuminate\Database\Eloquent\Model;

class UserTraces extends Model
{
    protected $fillable = ['id', 'user_id', 'readable_type', 'readable_id', 'action', 'created_at'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    private static function action($type, $id, $action) {
        UserTraces::create([
            'readable_id' => $id,
            'readable_type' => $type,
            'user_id' => Auth::id(),
            'action' => $action
        ]);
    }

    public static function translate($type, $id) {
        self::action($type, $id, ActionType::$translate);
    }

    public static function checkFr($type, $id) {
        self::action($type, $id, ActionType::$checkFr);
    }

    public static function validTranslate($type, $id) {
        self::action($type, $id, ActionType::$validTranslate);
    }

    public static function submitTranslate($type, $id) {
        self::action($type, $id, ActionType::$submitTranslate);
    }

    public static function submitCheckFr($type, $id) {
        self::action($type, $id, ActionType::$submitCheckFr);
    }

    public static function subscribe($type, $id) {
        self::action($type, $id, ActionType::$subscribe);
    }
}
