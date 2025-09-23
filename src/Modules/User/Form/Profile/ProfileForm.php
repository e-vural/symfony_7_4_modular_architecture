<?php

namespace App\Modules\User\Form\Profile;

use App\Modules\User\Entity\Profile\Profile;
use App\Modules\User\Form\UserForm;
use App\Shared\Form\BaseAbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileForm extends BaseAbstractForm
{


    public static function addToBuilder(FormBuilderInterface $builder, array $options = [])
    {

        $builder
            ->add('name',null,[
                "attr" => ["enterkeyhint" => "next","tabindex" =>1],
                "required" => $options["name_required"]
            ])
            ->add('surname',null, [
                "attr" => ["enterkeyhint" => "next","tabindex" =>1],
                "required" => $options["surname_required"]
            ])
//            ->add('user', UserForm::class,[
//                "required" => false,
//            ])
        ;

        if  (!$options["without_phone"]){
            $builder->add('phoneNumber',null,[
                "attr" => ["enterkeyhint" => "next","tabindex" =>1],
            ]);
        }

        return $builder;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        self::addToBuilder($builder, $options);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
            'csrf_protection' => false,
            'name_required' => true,
            'surname_required' => true,
            'without_phone' => false,
            'allow_extra_fields' => true
//            "compound" => false
        ]);
    }
}
