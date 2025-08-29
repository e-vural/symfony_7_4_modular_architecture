<?php

namespace App\Modules\User\Form\Register;

use App\Modules\User\Form\UserForm;
use App\Shared\Form\BaseAbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterForm extends BaseAbstractForm
{
    public static function addToBuilder(FormBuilderInterface $builder): FormBuilderInterface
    {

        $builder->add('user', UserForm::class);


        return $builder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        self::addToBuilder($builder);

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



