<?php

namespace App\Modules\User\Entity\Profile;


use App\Modules\User\Entity\User;
use App\Shared\Traits\Entity\Date\DatetimeTrait;
use App\Shared\Traits\Entity\PrimaryKey\PrimaryKeyTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[
    ORM\Entity(),
    ORM\Table(name: "profile"),
    ORM\HasLifecycleCallbacks
]
class Profile
{
    use DatetimeTrait, PrimaryKeyTrait;

    #[
        ORM\Column(length: 120, nullable: false),
        Assert\NotBlank(message: 'Profile name is required'),
    ]
    protected ?string $name = null;

    #[
        ORM\Column(length: 120, nullable: false),
        Assert\NotBlank(message: 'Profile surname is required')
    ]
    protected ?string $surname = null;

    #[ORM\Column( length: 250, nullable: true)]
    protected ?string $fullName = null;

    #[ORM\Column( length: 250, nullable: true)]
    protected ?string $phoneNumber = null;


    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'profile')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: "id", onDelete: "CASCADE")]
    protected User $user;

    #[
        ORM\PrePersist,
        ORM\PreUpdate,
    ]
    public function generateFullName(): void
    {
        $this->setFullname($this->name. " ". $this->surname);
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ?static
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): ?static
    {
        $this->surname = $surname;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
