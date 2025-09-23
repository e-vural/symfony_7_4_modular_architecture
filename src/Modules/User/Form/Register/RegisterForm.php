<?php

namespace App\Modules\User\Form\Register;

use App\Modules\User\Entity\Profile\Profile;
use App\Modules\User\Entity\User;
use App\Modules\User\Form\Profile\ProfileForm;
use App\Modules\User\Form\UserForm;
use App\Shared\Form\BaseAbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterForm extends BaseAbstractForm
{
    public static function addToBuilder(FormBuilderInterface $builder): FormBuilderInterface
    {
        $builder->add('user', UserForm::class);
        $builder->add('profile', ProfileForm::class);


//dump($builder);exit();
        return $builder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        self::addToBuilder($builder);



        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
//            $data = $form->getData();

//            dump($data);exit();
            $userForm = $form->get('user');
            $profileForm = $form->get('profile');

            if ($userForm->isSubmitted() && $userForm->isValid() && $profileForm->isSubmitted() && $profileForm->isValid()) {
                /** @var User $user */
                $user = $userForm->getData();
                /** @var Profile $profile */
                $profile = $profileForm->getData();

                $profile->setUser($user);
            }
        });

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



