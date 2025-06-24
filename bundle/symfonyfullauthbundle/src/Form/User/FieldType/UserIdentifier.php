<?php

namespace SymfonyFullAuthBundle\Form\User\FieldType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use SymfonyFullAuthBundle\Entity\User\User;
use SymfonyFullAuthBundle\Form\AbstractForm;

class UserIdentifier extends AbstractForm
{

    public static function addToBuilder(FormBuilderInterface $builder, array $options = []): FormBuilderInterface
    {
        $builder->add("identifier", UserIdentifier::class, $options);
        return $builder;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'property_path' => "email",
            "empty_data" => null,
            "attr" => ["autocomplete" => "new-password","enterkeyhint" => "next","tabindex" =>1],
            "label" => "Email",
            "data_class" => User::class,
//            "constraints" => [
//              new Email()
//            ],
            "documentation" =>[
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
