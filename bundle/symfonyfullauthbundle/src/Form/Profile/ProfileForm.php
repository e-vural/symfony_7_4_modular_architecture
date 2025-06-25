<?php

namespace SymfonyFullAuthBundle\Form\Profile;

use App\Entity\Profile\Profile;
use App\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileForm extends AbstractForm
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
