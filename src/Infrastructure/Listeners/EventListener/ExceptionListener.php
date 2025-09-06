<?php

namespace App\Infrastructure\Listeners\EventListener;

use App\Shared\Exception\FormException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener]
final class ExceptionListener
{

    public function __invoke(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();


        if($exception instanceof FormException){
            $response = new JsonResponse(["message" => $exception->getMessage(),"errors" => $exception->getErrors(false),"fromGlobalException" => true],$exception->getCode());
            $event->setResponse($response);
        }
    }
}
