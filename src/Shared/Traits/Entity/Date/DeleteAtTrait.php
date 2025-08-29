<?php

namespace App\Shared\Traits\Entity\Date;

use Doctrine\ORM\Mapping as ORM;

trait DeleteAtTrait
{
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deletedAt;


    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt = null): static
    {
        if (!$deletedAt){
            $deletedAt = new \DateTime();
        }

        $this->deletedAt = $deletedAt;
        return $this;
    }
}
