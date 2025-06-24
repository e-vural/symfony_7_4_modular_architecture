<?php

namespace SymfonyFullAuthBundle\Service\User;

use AllowDynamicProperties;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use SymfonyFullAuthBundle\Controller\View\Auth\LoginFailedException;
use SymfonyFullAuthBundle\Entity\User\User;

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
