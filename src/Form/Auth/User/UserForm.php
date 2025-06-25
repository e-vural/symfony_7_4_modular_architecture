<?php

namespace App\Form\Auth\User;

use App\Entity\Auth\User\User;
use App\Form\AbstractForm;
use App\Form\Auth\User\FieldType\UserIdentifier;
use App\Form\Auth\User\FieldType\UserPassword;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractForm
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** Identifier for login in Member entity **/
        $builder = UserIdentifier::addToBuilder($builder);

        /** Password for login in Member entity **/
//        $builder = UserPasswordType::addToBuilder($builder);

        /** TODO bu alan dışardan gelmeyecek. Biz kendimiz belirleyeceğiz. Mapped false olarak yapılacak olabilir. Ona bir bakalım. */
        $builder->add(UserPassword::CHILD_NAME,UserPassword::class);
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

