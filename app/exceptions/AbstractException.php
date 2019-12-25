<?php

namespace app\exceptions;


abstract class AbstractException extends \Exception
{
    protected $messages = [];

    protected $statusCode = 0;

    public function __construct(array $messages, string $message = "")
    {
        parent::__construct($message);

        $this->messages = $messages;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
