<?php

namespace App\Infrastructure\Traits\Entity\PrimaryKey;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;

trait PrimaryKeyUuidTrait
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    public function getId(): string
    {
        return $this->id;
    }
}
