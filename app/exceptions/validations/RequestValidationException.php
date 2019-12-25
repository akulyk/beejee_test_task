<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 31.01.19
 * Time: 17:36
 */

namespace app\exceptions\validations;


use app\exceptions\AbstractException;

class RequestValidationException extends AbstractException
{
    protected $statusCode = 422;
}
