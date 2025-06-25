<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use SymfonyFullAuthBundle\Form\FormException;

#[AsEventListener]
final class ExceptionListener
{

    public function __invoke(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();


        if($exception instanceof FormException){
            $response = new JsonResponse(["message" => $exception->getMessage(),"errors" => $exception->getErrors(),"fromGlobalException" => true]);
            $event->setResponse($response);
        }
    }
}
