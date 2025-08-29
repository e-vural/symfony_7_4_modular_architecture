<?php

namespace App\Shared\Validator\Password;

use Symfony\Component\Validator\Constraint;

class PasswordStrength extends Constraint
{

    public array $messages = [
        "Your new password value is weak. Please follow password rules",
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
