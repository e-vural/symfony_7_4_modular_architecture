<?php

namespace App\Security\Authentication;

use AllowDynamicProperties;
use JetBrains\PhpStorm\NoReturn;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;


#[AllowDynamicProperties]
#[AsEventListener(event: 'lexik_jwt_authentication.on_jwt_created', method: 'onJWTCreated')]
class JWTCreatedListener
{
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[NoReturn] public function onJWTCreated(JWTCreatedEvent $event): void
    {

        $payload       = $event->getData();

//        dd($payload);
//        $header = $event->getHeader();

//        $payload["test"] = "123124124";

        $event->setData($payload);
    }
}
