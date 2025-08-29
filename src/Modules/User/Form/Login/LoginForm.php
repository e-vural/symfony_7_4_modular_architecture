<?php

namespace App\Modules\User\Form\Login;

use App\Modules\User\FormType\User\UserIdentifierFormType;
use App\Shared\Form\BaseAbstractForm;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginForm extends BaseAbstractForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

//        $builder = UserIdentifier::addToBuilder($builder);
        $builder->add(UserIdentifierFormType::CHILD_NAME,UserIdentifierFormType::class,[
            "property_path" => null,
            "required" => true,
        ]);
        $builder
            ->add('password', PasswordType::class, [
                'required' => true,
                "constraints" => [new NotBlank()]

            ]);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            "mapped" => false,
            'csrf_protection' => false,

        ]);
    }
}
