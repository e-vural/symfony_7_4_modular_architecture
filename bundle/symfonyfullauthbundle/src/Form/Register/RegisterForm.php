<?php

namespace SymfonyFullAuthBundle\Form\Register;

use App\Services\User\UserAccountDetail\UserAccountDetailService;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonyFullAuthBundle\Form\AbstractForm;
use SymfonyFullAuthBundle\Form\Profile\ProfileForm;
use SymfonyFullAuthBundle\Form\User\UserForm;

class RegisterForm extends AbstractForm
{
    public static function addToBuilder(FormBuilderInterface $builder): FormBuilderInterface
    {
        // TODO addToBuilder ile aşağıdaki buildForm niye var. Bu zaten birleştirilmiş bir form yapısı. Bu dışarıda ek olarak kullanılır mı ? Ayrıca UI tarafını da karşılıyor burası. Değiştirilmezse iyi olur.
        $builder
            ->add('profile', ProfileForm::class)
            ->add('user', UserForm::class)
            ->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event) {
                if ($event->getForm()->isValid()) {
                    $user = $event->getData()["user"];
                    $profile = $event->getData()["profile"];

                    $profile->setUser($user);
                }
            })
        ;

        return $builder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        self::addToBuilder($builder);
        $builder
            ->add('profile', ProfileForm::class)
            ->add('user', UserForm::class)
            ->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event){
                /** User account detail set process */
                if ($event->getForm()->isValid()) {
                    $userAccountDetail = (new UserAccountDetailService())->setUserAccountDetail($event->getData()["user"]);/** We will set with default values to user account detail. Because all values on the register side are real and meet the default values since mail is not verify */

                    $em = $this->getEntityManager();
                    $em->persist($userAccountDetail);
                    $em->flush();
                }
                /** */
            })
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            "attr" => ["autocomplete" => "off", "novalidate" => "novalidate"],
        ]);
    }
}
