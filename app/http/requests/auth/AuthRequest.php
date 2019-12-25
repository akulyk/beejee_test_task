<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 28.01.19
 * Time: 20:09
 */

namespace app\http\requests\auth;


use app\http\requests\AbstractRequest;

class AuthRequest extends AbstractRequest
{
    protected $throwExceptionOnError = false;

    public function rules(): array
    {
        return [
            'username' => ['required', 'min:3'],
            'password' => ['required', 'min:3'],
        ];
    }

}
