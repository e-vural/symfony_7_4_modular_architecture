<?php

namespace SymfonyFullAuthBundle\Entity\Profile;

use App\Core\DatabaseSchema;
use App\Serializer\SerializerGroups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use SymfonyFullAuthBundle\Entity\Traits\DatetimeTrait;
use SymfonyFullAuthBundle\Entity\Traits\PrimaryKeyTrait;
use SymfonyFullAuthBundle\Entity\User\User;


#[
    ORM\Entity(),
    ORM\Table(name: "profile"),
    ORM\HasLifecycleCallbacks
]
class Profile implements ProfileInterface
{
    use DatetimeTrait, PrimaryKeyTrait;
//
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
//    protected ?int $id = null;


    #[
        ORM\Column(length: 120, nullable: false),
        Assert\NotBlank(message: 'Profile name is required'),
    ]
    #[Groups(SerializerGroups::PUSH_NOTIFICATION)]
    protected ?string $name = null;


    #[
        ORM\Column(length: 120, nullable: false),
        Assert\NotBlank(message: 'Profile surname is required')
    ]
    #[Groups(SerializerGroups::PUBLIC)]
    protected ?string $surname = null;


    #[ORM\Column( length: 250, nullable: true)]
    #[Groups(SerializerGroups::PUBLIC)]
    protected ?string $fullName = null;



    #[ORM\Column( length: 250, nullable: true)]
    #[Groups(SerializerGroups::PUBLIC)]
    protected ?string $phoneNumber = null;



    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'profile')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: "id", onDelete: "CASCADE")]
    #[Groups(SerializerGroups::USER)]
    protected User $user;


    #[
        ORM\PrePersist,
        ORM\PreUpdate,
    ]
    public function generateFullName(): void
    {
        $this->setFullname($this->name. " ". $this->surname);
    }


//    public function getId(): ?string
//    {
//        return $this->id;
//    }

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
