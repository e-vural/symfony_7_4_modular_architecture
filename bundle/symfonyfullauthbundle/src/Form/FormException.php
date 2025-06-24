<?php

namespace SymfonyFullAuthBundle\Form;

class FormException extends \Exception
{

    private array $errors = [];
    public function __construct(string $message = "", array $errors = [],int $code = 0, ?\Throwable $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getErrors($encodeJson = true): array|string
    {
        if($encodeJson) {
            return json_encode($this->errors);
        }
        return $this->errors;
    }




}
