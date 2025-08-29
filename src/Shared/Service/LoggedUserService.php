<?php

namespace App\Shared\Service;

use App\Modules\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LoggedUserService
{

    public function __construct(readonly TokenStorageInterface $tokenStorage)
    {

    }

    public function getUser(): ?UserInterface
    {

        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        return $token->getUser();
    }


}
