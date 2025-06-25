<?php

namespace App\Form;

use AllowDynamicProperties;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

#[AllowDynamicProperties] abstract class AbstractForm extends AbstractType implements ServiceSubscriberInterface
{
    use ServiceMethodsSubscriberTrait;

    public static function getSubscribedServices(): array
    {
        return [
            EntityManagerInterface::class
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->container->get(EntityManagerInterface::class);
    }


    /**
     * @throws Exception
     */
    public function formIsValid($form): void
    {
        if (!$form->isValid()){
            throw new Exception("Form is not valid");
        }
    }

//    public function postSubmitProcesses(PostSubmitEvent $event): void
//    {
//        $this->entityControl($event);
//    }

//    public function preSubmitProcesses(PreSubmitEvent $event): void
//    {
//        $this->requestChecker($event);
//    }



//    public function entityControl(PostSubmitEvent $event): void
//    {
//        $entity = $event->getData();
//        $form = $event->getForm();
//
//
//        $formErrors = $form->getErrors(true);
//        if($formErrors->count()){
//            $errors = [];
//            /** @var FormError $formError */
//            foreach ($formErrors as $formError) {
//                $errors[$formError->getOrigin()->getName()][] = $formError->getMessage();
//            }
//
//            dd($errors);
//
//        }
//        dd($event->getForm()->isValid(),$event->getForm()->getName());
//        if($entity instanceof Member){
//            dd($event->getForm());
//            dd($event->getForm()->get("identifier")->getErrors());
//        }

//        $errors = $this->validator->validate($entity);
////        dd($errors);
//        if (count($errors) > 0){
//            foreach ($errors as $error)
//            {
//                throw new Exception($error->getMessage());
//            }
//        }
//    }


    /** Checker for XSS eq. It uses in the prePersist event of the Form */
    protected function requestChecker(PreSubmitEvent $event): void
    {
        $cleanData = [];
        $data = $event->getData();
        foreach ($data as $k => $v)
        {
            /** Member da roles gibi bir değer geliyor array olarak. Onu atlamak için yapıldı. Ama onun için de bir şey düşünülebilir. Yapı olarak iç içe array şeklinde bir data buraya gelemez. Onun için gerek var mı bilemedim */
            if (!is_string($v)){
                $cleanData[$k] = $v;
            }else{
                $cleanVal = htmlspecialchars(strip_tags($v), ENT_QUOTES, 'UTF-8');
                $cleanData[$k] = $cleanVal;
            }
            /** */
        }

        $event->setData($cleanData);
    }
}
