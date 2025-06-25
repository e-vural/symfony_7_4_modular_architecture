<?php

namespace App\Form\Auth\Login;

use App\Form\AbstractForm;
use App\Form\Auth\User\FieldType\UserIdentifier;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder = UserIdentifier::addToBuilder($builder);
        $builder
            ->add('password', PasswordType::class, [
                'required' => true,
                "data" => "Password1!"
            ]);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => false,

        ]);
    }
}
