<?php
use App\Lesson;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/24
 * Time: 14:12
 */

trait SluggableController
{
    private function findOrFail($idOrSlug) {
        if(is_numeric($idOrSlug)) {
            return Lesson::findOrFail($idOrSlug);
        } else {
            $lesson = Lesson::where('slug', $idOrSlug)->first();
            if($lesson) {
                return $lesson;
            } else {
                throw (new ModelNotFoundException)->setModel(get_class(Lesson::class));
            }
        }
    }
}