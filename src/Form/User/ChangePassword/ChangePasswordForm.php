<?php

namespace App\Form\User\ChangePassword;

use App\Form\Auth\ResetPassword\ResetPasswordForm;
use App\Form\MyAbstractForm;
use App\Validator\Password\PasswordValidation;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChangePasswordForm extends MyAbstractForm
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

        $builder = ResetPasswordForm::addToBuilder($builder);

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
