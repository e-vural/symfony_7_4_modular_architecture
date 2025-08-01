<?php

namespace App\Service\User;

use AllowDynamicProperties;
use App\Entity\User\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


#[AllowDynamicProperties] class PasswordService
{

    private TokenStorageInterface $tokenStorage;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(TokenStorageInterface $tokenStorage, UserPasswordHasherInterface $userPasswordHasher)
    {

        $this->tokenStorage = $tokenStorage;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function isPasswordValid(string $password, User $user = null): bool
    {
        return $this->userPasswordHasher->isPasswordValid($user ?? $this->getUser(), $password);
    }

    public function change(string $newPassword, User $user = null): Response
    {

        if (!$user) {
            $user = $this->getUser();
        }
        $user->setPassword($newPassword);


    }

    private function getUser()
    {

        $token = $this->tokenStorage->getToken();
        return $token->getUser();

    }

}
