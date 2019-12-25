<?php namespace app\http\requests\todos;

use app\http\requests\AbstractRequest;
use app\models\todo\Todo;

class TodoRequest extends AbstractRequest
{
    public $throwExceptionOnError = false;

    public function rules(): array
    {
        return [
            Todo::FIELD_USERNAME => ['required', 'min:3','regex:/(^([a-zA-Z]+)(\d+)?$)/u'],
            Todo::FIELD_EMAIL => ['required', 'email',],
            Todo::FIELD_TEXT => ['required','string']
        ];
    }

}
