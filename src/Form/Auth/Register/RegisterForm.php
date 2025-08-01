<?php

namespace App\Form\Auth\Register;

use App\Form\MyAbstractForm;
use App\Form\User\UserForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterForm extends MyAbstractForm
{
    public static function addToBuilder(FormBuilderInterface $builder): FormBuilderInterface
    {

//        $builder = UserIdentifier::addToBuilder($builder);
//        UserForm::addToBuilder($builder);
//        // TODO addToBuilder ile aşağıdaki buildForm niye var. Bu zaten birleştirilmiş bir form yapısı. Bu dışarıda ek olarak kullanılır mı ? Ayrıca UI tarafını da karşılıyor burası. Değiştirilmezse iyi olur.
        $builder
//////            ->add('profile', ProfileForm::class)
            ->add('user', UserForm::class)
//            ->add('identifier', UserIdentifier::class)
//            ->add('password', UserPassword::class)
////            ->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event) {
////
////            })
        ;

        return $builder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        self::addToBuilder($builder);

//        $builder
//            ->add('user', UserForm::class);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => false,
            "attr" => ["autocomplete" => "off", "novalidate" => "novalidate"],
        ]);
    }

}



