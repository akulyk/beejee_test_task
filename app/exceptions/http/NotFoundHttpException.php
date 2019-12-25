<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 31.01.19
 * Time: 20:06
 */

namespace app\exceptions\http;


use app\exceptions\AbstractException;

class NotFoundHttpException extends AbstractException
{
    protected $statusCode = 404;
}
