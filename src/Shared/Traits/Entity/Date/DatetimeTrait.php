<?php

namespace App\Shared\Traits\Entity\Date;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait DatetimeTrait
{
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $createdAt = null;


    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt = null): static
    {
        if (!$createdAt){
            $createdAt = new DateTimeImmutable();
        }

        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt() : ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt = null): static
    {
        if (!$updatedAt){
            $updatedAt = new DateTimeImmutable();
        }

        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $currentTime = new DateTimeImmutable();
        $this->setUpdatedAt($currentTime);

        if ($this?->getCreatedAt() == null) {
            $this->setCreatedAt($currentTime);
        }
    }
}
