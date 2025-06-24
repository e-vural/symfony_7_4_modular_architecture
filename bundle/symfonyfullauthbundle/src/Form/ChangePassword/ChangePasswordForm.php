<?php

namespace SymfonyFullAuthBundle\Form\ChangePassword;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use SymfonyFullAuthBundle\Form\AbstractForm;
use SymfonyFullAuthBundle\Form\ResetPassword\ResetPasswordForm;
use SymfonyFullAuthBundle\Validator\Password\PasswordValidation;

class ChangePasswordForm extends AbstractForm
{
    public static function getSubscribedServices(): array
    {
        return array_merge([
            TokenStorageInterface::class,
        ], parent::getSubscribedServices());
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getTokenStorage(): TokenStorageInterface
    {
        return $this->container->get(TokenStorageInterface::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('oldPassword', PasswordType::class, [
                'mapped' => false,
                "constraints" => [
                    new PasswordValidation()
                ]
            ]);
//        ->add("newPassword", ResetPasswordFormType::class);

        $builder = ResetPasswordForm::addToBuilder($builder);


//        $builder->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event){
        //TODO old password kontrol


//                $this->requestChecker($event);
//                /** @var Member $user */
//                $user = $this->tokenStorage->getToken()->getUser();

//                $formData = $event->getForm()->getData();

//                dd($event->getForm()->get("newPassword")->getData());
//                $newPassword = $event->getForm()->get("newPassword")->getData();

//                dd($newPassword);
//                $user->setPassword($newPassword);
//                $user = $this->tokenStorage->getToken()->getUser();
//                dd($user);
//                $user = @$data["user"];
//                $event->getForm()->setData($user);

//                $checkOldPassword = $this->passwordHasher->isPasswordValid($user, $oldPassword);
//                if (!$checkOldPassword) {
//                    throw new \Exception("Member password not true");
//                }
//            })
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            "mapped" => false,
//            'data_class' => Member::class,
            'csrf_protection' => false,
        ]);
    }
}
