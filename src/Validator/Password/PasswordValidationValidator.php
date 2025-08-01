<?php

namespace App\Validator\Password;

use App\Service\User\PasswordService;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordValidationValidator extends ConstraintValidator
{

    private PasswordService $passwordService;

    public function __construct( PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    public function validate(mixed $value, Constraint $constraint)
    {
//        dd($constraint);
        if (!$constraint instanceof PasswordValidation) {
            throw new UnexpectedTypeException($constraint, PasswordValidation::class);
        }


//        dd($isPasswordValid);
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

        $isPasswordValid = $this->passwordService->isPasswordValid($value);

        if($isPasswordValid){
            return;
        }


        foreach ($constraint->messages as $message) {
            // the argument must be a string or an object implementing __toString()
            $this->context->buildViolation($message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }


        // TODO: Implement validate() method.
    }
}
