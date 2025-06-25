<?php

namespace App\Form\Auth\Register;

use App\Form\AbstractForm;
use App\Form\Auth\User\UserForm;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonyFullAuthBundle\Form\Profile\ProfileForm;

class RegisterForm extends AbstractForm
{
    public static function addToBuilder(FormBuilderInterface $builder): FormBuilderInterface
    {
        // TODO addToBuilder ile aşağıdaki buildForm niye var. Bu zaten birleştirilmiş bir form yapısı. Bu dışarıda ek olarak kullanılır mı ? Ayrıca UI tarafını da karşılıyor burası. Değiştirilmezse iyi olur.
        $builder
//            ->add('profile', ProfileForm::class)
            ->add('user', UserForm::class)
            ->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event) {

            })
        ;

        return $builder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        self::addToBuilder($builder);

        $builder
            ->add('user', UserForm::class);
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



