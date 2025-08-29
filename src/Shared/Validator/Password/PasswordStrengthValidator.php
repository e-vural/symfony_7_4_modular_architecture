<?php

namespace App\Shared\Validator\Password;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordStrengthValidator extends ConstraintValidator
{

    public function validate(mixed $value, Constraint $constraint)
    {
//        dd($constraint);
        if (!$constraint instanceof PasswordStrength) {
            throw new UnexpectedTypeException($constraint, PasswordStrength::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new \UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

//        // access your configuration options like this:
//        if ('strict' === $constraint->mode) {
//            // ...
//        }
        $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";

//        dd(preg_match($password_regex, $value, $matches));
        if (preg_match($password_regex, $value, $matches)) {
            return;
        }



        foreach ($constraint->messages as $message) {
            // the argument must be a string or an object implementing __toString()
            $this->context->buildViolation($message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }


    }
}
