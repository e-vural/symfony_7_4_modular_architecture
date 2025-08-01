<?php

namespace App\Validator\Password;

use Symfony\Component\Validator\Constraint;
use function PHPUnit\Framework\isEmpty;

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
        $this->messages = isEmpty($messages) ? $this->messages : $messages;

    }
}
