<?php

namespace App\Infrastructure\FormType\User;

use App\Validator\Password\PasswordStrength;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserPasswordFormType extends AbstractType
{
   const CHILD_NAME = "password";

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefault("constraints",[
            new PasswordStrength(),
        ]);

//        $help = "The password must be at least 8 characters long, consist of a number, an uppercase and a lowercase letter";
        $help = "";
        $resolver->setDefaults(
            [
                "attr" => ["autocomplete" => "new-password","class" => "password-strength-meter"],
                "required" => true,
                "empty_data" => null,
                "help" =>"$help",
                'property_path' => "password",
//                "data_class" => User::class,
                "documentation" => ["type" => "string", "description" => "$help",]
            ]);
    }

    public function getParent()
    {
        return PasswordType::class;

    }
}
