<?php

namespace App\Modules\User\Form;

use App\Modules\User\Entity\User;
use App\Modules\User\FormType\User\PasswordFormType;
use App\Modules\User\FormType\User\UserIdentifierFormType;
use App\Shared\Form\BaseAbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends BaseAbstractForm
{

    public static function addToBuilder(FormBuilderInterface $builder): FormBuilderInterface{


        /** Password for login in Member entity **/
        $builder->add(UserIdentifierFormType::CHILD_NAME,UserIdentifierFormType::class);
//        UserIdentifierFormType::addToBuilder($builder);
        $builder->add(PasswordFormType::CHILD_NAME,PasswordFormType::class);

        return $builder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        self::addToBuilder($builder);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
        ]);

    }

}

