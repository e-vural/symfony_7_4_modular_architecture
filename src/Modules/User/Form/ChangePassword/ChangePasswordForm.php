<?php

namespace App\Modules\User\Form\ChangePassword;

use App\Modules\User\Form\ResetPassword\ResetPasswordForm;
use App\Shared\Form\BaseAbstractForm;
use App\Shared\Validator\Password\PasswordValidation;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChangePasswordForm extends BaseAbstractForm
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('oldPassword', PasswordType::class, [
                'mapped' => false,
                "constraints" => [
                    new PasswordValidation()
                ]
            ]);

        $builder = ResetPasswordForm::addToBuilder($builder);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

//            "mapped" => false,
//            'data_class' => Member::class,
            'csrf_protection' => false,
        ]);
    }


}
