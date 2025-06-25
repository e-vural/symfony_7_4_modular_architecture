<?php

namespace App\Infrastructure\Traits\Entity\PrimaryKey;

use Doctrine\ORM\Mapping as ORM;

trait PrimaryKeyIdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): string
    {
        return $this->id;
    }

}
