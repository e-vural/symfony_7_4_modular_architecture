<?php

namespace App\Shared\Exception;

use App\Infrastructure\Exception\AbstractHttpResponseException;

class FormException extends AbstractHttpResponseException
{

    public function __construct(string $message = "", array $errors = [], ?\Throwable $previous = null)
    {
        parent::__construct($message, $errors, 422, $previous);
    }


}
