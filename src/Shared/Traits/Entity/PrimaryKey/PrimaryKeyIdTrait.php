<?php

namespace App\Shared\Traits\Entity\PrimaryKey;

use Doctrine\ORM\Mapping as ORM;

trait PrimaryKeyIdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

}
