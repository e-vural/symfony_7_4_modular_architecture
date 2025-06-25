<?php

namespace App\Entity\Auth\User;

use App\Entity\Profile\Profile;
use App\Infrastructure\Traits\Entity\Date\DatetimeTrait;
use App\Infrastructure\Traits\Entity\Date\DeleteAtTrait;
use App\Infrastructure\Traits\Entity\PrimaryKey\PrimaryKeyTrait;
use App\Repository\Auth\User\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[
    ORM\Entity(repositoryClass: UserRepository::class),
    ORM\Table(name: 'a_user'),
    UniqueEntity(
        fields: ['email'],
        message: 'This mail already exists on the system'
    ),
    ORM\HasLifecycleCallbacks
]
class User implements  PasswordAuthenticatedUserInterface, BaseUserInterface
{

    use DatetimeTrait, PrimaryKeyTrait, DeleteAtTrait;


//    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
//    {
//
//        dd("updagrade passewod");
//        // TODO: Implement upgradePassword() method.
//    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email()]
    #[Assert\NotBlank(message: 'E-Mail is required')]
    protected ?string $email;


    #[ORM\Column]
    protected array $roles = [];


    #[ORM\Column]
    #[
        Assert\NotBlank(message: 'Password is required')
    ]
    protected ?string $password = null;


    #[ORM\OneToOne(targetEntity: Profile::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Profile $profile;



    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        // unset the owning side of the relation if necessary
        if ($profile === null && $this->profile !== null) {
            $this->profile->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($profile !== null && $profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        $this->profile = $profile;

        return $this;
    }


}
