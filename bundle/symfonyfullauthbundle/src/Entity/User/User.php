<?php

namespace SymfonyFullAuthBundle\Entity\User;

use App\Core\DatabaseSchema;
use App\Core\Roles\UserRole;
use App\Entity\MobileDevice\MobileDevice;
use App\Entity\Organization\OrganizationMember\OrganizationMember;
use App\Entity\Workspace\Workspace;
use App\Serializer\SerializerGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use SymfonyFullAuthBundle\Entity\Profile\Profile;
use SymfonyFullAuthBundle\Entity\Traits\DatetimeTrait;
use SymfonyFullAuthBundle\Entity\Traits\DeleteAtTrait;
use SymfonyFullAuthBundle\Entity\Traits\PrimaryKeyTrait;
use SymfonyFullAuthBundle\Repository\User\UserRepository;


#[
    ORM\Entity(repositoryClass: UserRepository::class),
    ORM\Table(name: 'user'),
    UniqueEntity(
        fields: ['email'],
        message: 'This mail already exists on the system'
    ),
    ORM\HasLifecycleCallbacks
]
class User implements UserInterface
{

    use DatetimeTrait, PrimaryKeyTrait, DeleteAtTrait;

//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
//    protected ?int $id = null;


    public function isClientUser(): bool
    {
        return in_array(UserRole::ROLE_CLIENT, $this->roles);
    }


    public function isStaffUser(): bool
    {
        return in_array(UserRole::ROLE_STAFF, $this->roles);
    }

    public function __toString(): string
    {

        return $this->id;
    }

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email()]
    #[Assert\NotBlank(message: 'E-Mail is required')]
//    #[JMS\Groups(["after_login"])]
    #[Groups([SerializerGroups::AFTER_LOGIN, SerializerGroups::PUBLIC])]
    protected ?string $email;


    #[ORM\Column]
    protected array $roles = [];


    #[ORM\Column]
//    #[JMS\Groups([SerializerGroups::PRIVATE])]

    #[
//        Assert\Length(
//            min: 8,
//            minMessage: 'Your password must be at least {{ limit }} characters long',
//        ),
        Assert\NotBlank(message: 'Password is required')
    ]
    protected ?string $password = null;


    #[ORM\OneToOne(targetEntity: Profile::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
//    #[JMS\Groups([SerializerGroups::AFTER_LOGIN])]
    #[Groups([SerializerGroups::AFTER_LOGIN, SerializerGroups::PUBLIC])]
    private ?Profile $profile;


    #[ORM\OneToOne(targetEntity: UserAccountDetail::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
//    #[JMS\Groups([SerializerGroups::AFTER_LOGIN])]
    #[Groups([SerializerGroups::AFTER_LOGIN, SerializerGroups::PUBLIC])]
    private ?UserAccountDetail $accountDetail;


    public function __construct()
    {

    }


    public function getId(): string
    {
        return $this->id;

    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): ?static
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRole::ROLE_USER;

        return array_unique($roles);
    }


    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $baseRoles = ["ROLE_USER"];
        $roles = array_merge($baseRoles, $roles);
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
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

    public function setProfile(?Profile $profile): self
    {
        if ($profile === null && $this->profile !== null) {
            $this->profile->setUser(null);
        }

        if ($profile !== null && $profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        $this->profile = $profile;
        return $this;
    }






}
