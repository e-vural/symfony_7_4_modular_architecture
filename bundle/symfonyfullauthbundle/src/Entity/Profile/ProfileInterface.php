<?php

namespace SymfonyFullAuthBundle\Entity\Profile;


use SymfonyFullAuthBundle\Entity\User\User;

interface ProfileInterface
{
    public function getId(): ?string;

    public function getName(): ?string;

    public function setName(?string $name): ?static;

    public function getSurname(): ?string;

    public function setSurname(?string $surname): ?static;

    public function getFullName(): ?string;

    public function setFullName(string $fullName): static;

    public function getUser(): ?User;

    public function setUser(?User $user): self;
}
