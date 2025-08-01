<?php

namespace App\Form\Auth\ResetPassword;

use App\Form\MyAbstractForm;
use App\Infrastructure\FormType\User\UserPasswordFormType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordForm extends MyAbstractForm
{

    public static function addToBuilder(FormBuilderInterface $builder)
    {
        $builder
            ->add('newPassword', RepeatedType::class, [
                'type' => UserPasswordFormType::class,
                'options' => [
                    'property_path' => null,
                ],
                'first_options' => [
                    'label' => 'New password',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'constraints' => [],
                    "help" => null
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ]);

        return $builder;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        self::addToBuilder($builder);
//            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
//
//
//        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(["label" =>"","mapped" => false]);
    }
//    public function getBlockPrefix()
//    {
//        return "abo";
//    }
}
