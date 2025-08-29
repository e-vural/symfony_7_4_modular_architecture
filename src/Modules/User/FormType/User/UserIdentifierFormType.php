<?php

namespace App\Modules\User\FormType\User;

use App\Modules\User\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserIdentifierFormType extends AbstractType
{
    const CHILD_NAME = "identifier";

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "constraints" => [new Email(),new NotBlank()],
            'property_path' => "email",
            "empty_data" => null,
            "data_class" => User::class,
//            "constraints" => [
//              new Email()
//            ],
            "documentation" => [
                "type" => "string",
                "description" => "That is the login identifier of the user. Eg: email, username ",
            ]
        ]);
    }

    public function getParent(): string
    {
        return EmailType::class;
    }
}
