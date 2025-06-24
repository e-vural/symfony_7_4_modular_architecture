<?php

namespace SymfonyFullAuthBundle\Entity\User;


use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;
use SymfonyFullAuthBundle\Entity\Profile\Profile;

interface UserInterface extends PasswordAuthenticatedUserInterface, BaseUserInterface
{
    public function getId(): ?string;

    public function getEmail(): ?string;

    public function setEmail(string $email): ?static;

    public function getProfile(): ?Profile;

    public function setProfile(?Profile $profile): self;
}
