<?php

namespace SymfonyFullAuthBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

#[AsEventListener(event: FormEvent::class,method: "postSubmit",priority: 500)]
class FormEventSubscriber
{

//    public static function getSubscribedEvents()
//    {
//        return [
//            FormEvents::POST_SUBMIT => 'postSubmit',
//        ];
//    }

    public function postSubmit(FormEvent $event){
        dd("FormEventSubscriber",$event);
    }
}
