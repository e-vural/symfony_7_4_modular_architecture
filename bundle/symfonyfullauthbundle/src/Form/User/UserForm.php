<?php

namespace SymfonyFullAuthBundle\Form\User;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonyFullAuthBundle\Entity\User\User;
use SymfonyFullAuthBundle\Form\AbstractForm;
use SymfonyFullAuthBundle\Form\User\FieldType\UserIdentifier;
use SymfonyFullAuthBundle\Form\User\FieldType\UserPassword;

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



//    private function assignRole(PostSubmitEvent $event): void
//    {
//        $user = $event->getData();
//        $userRole = ["ROLE_USER", "ROLE_ADMIN"];
//        $user->setRoles($userRole);
//    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
        ]);

        $resolver->setDefined('xss');
        $resolver->setAllowedTypes('xss', 'bool'); // sadece boolean türüne izin veriyoruz
    }


//    public function postSubmitProcesses(PostSubmitEvent $event): void
//    {
//        dd($event->getForm()->isValid(),$event->getForm()->getErrors());
//        $this->passwordEncoder($event);
//        $this->assignRole($event);
//        $this->entityControl($event);
//    }
}
