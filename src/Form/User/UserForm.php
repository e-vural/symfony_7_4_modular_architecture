<?php

namespace App\Form\User;

use App\Entity\User\User;
use App\Form\MyAbstractForm;
use App\Infrastructure\FormType\User\UserIdentifierFormType;
use App\Infrastructure\FormType\User\UserPasswordFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends MyAbstractForm
{

    public static function addToBuilder(FormBuilderInterface $builder): FormBuilderInterface{
//        $builder = UserIdentifier::addToBuilder($builder);
//        $builder = UserPassword::addToBuilder($builder);

        /** Password for login in Member entity **/
//        $builder = UserPasswordType::addToBuilder($builder);

        /** TODO bu alan dışardan gelmeyecek. Biz kendimiz belirleyeceğiz. Mapped false olarak yapılacak olabilir. Ona bir bakalım. */
        $builder->add(UserIdentifierFormType::CHILD_NAME,UserIdentifierFormType::class);
        $builder->add(UserPasswordFormType::CHILD_NAME,UserPasswordFormType::class);
//        $builder->add("password");

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

        $resolver->setDefined('xss');
        $resolver->setAllowedTypes('xss', 'bool'); // sadece boolean türüne izin veriyoruz
    }

}

