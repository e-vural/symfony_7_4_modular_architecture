<?php

namespace SymfonyFullAuthBundle\Entity\Traits;

use App\Serializer\SerializerGroups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait DeleteAtTrait
{
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups([SerializerGroups::PUBLIC])]
    private $deletedAt;


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
