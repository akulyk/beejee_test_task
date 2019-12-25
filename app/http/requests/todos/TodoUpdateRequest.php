<?php namespace app\http\requests\todos;

use app\http\requests\AbstractRequest;
use app\models\todo\Todo;

class TodoUpdateRequest extends AbstractRequest
{
    public $throwExceptionOnError = false;

    public function rules(): array
    {
        return [
            Todo::FIELD_TEXT => ['required','string']
        ];
    }

}
