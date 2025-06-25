<?php

namespace App\Form\Auth\User;

use App\Entity\Auth\User\UserAccountDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAccountDetailForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('realEmail')
            ->add('emailVerified')
            ->add('realPassword')
            ->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event){
                $data = $event->getData();
                $data['realEmail'] = @$data['realEmail'] ?? true;
                $data['emailVerified'] = @$data['emailVerified'] ?? false;
                $data['realPassword'] = @$data['realPassword'] ?? true;
                $event->setData($data);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAccountDetail::class,
            'csrf_protection' => false
        ]);
    }
}
