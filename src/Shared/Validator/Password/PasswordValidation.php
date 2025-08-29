<?php

namespace App\Shared\Validator\Password;

use Symfony\Component\Validator\Constraint;

class PasswordValidation extends Constraint
{

    public array $messages = [
        "Password not valid",
    ];
    public string $mode = 'strict';

    // all configurable options must be passed to the constructor
    public function __construct(?array $messages = [], ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

//        $this->mode = $mode ?? $this->mode;
        $this->messages = empty($messages) ? $this->messages : $messages;

    }
}
