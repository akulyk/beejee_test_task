<?php

namespace app\models\todo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class Product
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $text
 * @property string $status
 *
 * @package app\models\products
 */
class Todo extends Model
{
    const FIELD_ID = 'id';
    const FIELD_USERNAME = 'username';
    const FIELD_EMAIL = 'email';
    const FIELD_TEXT = 'text';
    const FIELD_STATUS = 'status';

    protected $table = 'todo';

    public $timestamps = false;

    public function scopeOrdered($query, Request $request)
    {
        if($field = $request->get('sort')){
           if(strpos($field,'-') === false){
               $direction = 'ASC';
           }else{
               $direction = 'DESC';
               $field = ltrim($field,'-');
           }
        }else{
            $field = 'id';
            $direction = 'DESC';
        }

        return $query->orderBy($field, $direction);
    }

}
