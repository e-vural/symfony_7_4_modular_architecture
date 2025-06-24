<?php

namespace SymfonyFullAuthBundle\Form\Login;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonyFullAuthBundle\Form\AbstractForm;

class LoginForm extends AbstractForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
//                "data" => "orhan.staff@kodpit.com",
            ]);
        $builder
            ->add('password', PasswordType::class, [
                'required' => true,
//                "data" => "Password1!",
            ]);

//        $builder = UserPasswordType::addToBuilder($builder);

//        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event){

//            $event->getForm()->addError((new FormError("Passwrod hatalı")));
//            dump("LoginType pre submit");
//        });
//        $builder->add("submit", SubmitType::class, []);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => false,

        ]);
    }
}
