<?php

namespace SymfonyFullAuthBundle\Security;

use AllowDynamicProperties;
use JetBrains\PhpStorm\NoReturn;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

#[AllowDynamicProperties]class JWTCreatedListener
{
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }


    #[NoReturn] public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload       = $event->getData();
//        $header = $event->getHeader();

//        $payload["test"] = "123124124";

        $event->setData($payload);
    }
}
