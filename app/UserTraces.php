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
        self::action($type, $id, ActionType::TRANSLATE);
    }

    public static function checkFr($type, $id) {
        self::action($type, $id, ActionType::CHECK_FR);
    }

    public static function validTranslate($type, $id) {
        self::action($type, $id, ActionType::VALID_TRANSLATE);
    }

    public static function submitTranslate($type, $id) {
        self::action($type, $id, ActionType::SUBMIT_TRANLSATE);
    }

    public static function submitCheckFr($type, $id) {
        self::action($type, $id, ActionType::SUBMIT_CHECK_FR);
    }

    public static function subscribe($type, $id) {
        self::action($type, $id, ActionType::SUBSCRIBE);
    }

    public static function checkZh($type, $id) {
        self::action($type, $id, ActionType::CHECK_ZH);
    }
}
