<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Form\FormEvent;

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
