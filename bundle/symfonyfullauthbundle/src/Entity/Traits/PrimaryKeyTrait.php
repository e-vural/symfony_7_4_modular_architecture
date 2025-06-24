<?php

namespace SymfonyFullAuthBundle\Entity\Traits;

use App\Serializer\SerializerGroups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

trait PrimaryKeyTrait
{
    // TODO Symfony component uid için readme içerisine info ekle
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups([SerializerGroups::PUBLIC])]
    private string $id;

    public function getId(): string
    {
        return $this->id;
    }

//    #[ORM\PrePersist]
//    public function prePersist(): void
//    {
//        if (empty($this->id)) {
//            $this->id = Uuid::v7();
//        }
//    }
}
