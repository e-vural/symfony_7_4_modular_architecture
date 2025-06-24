<?php

namespace SymfonyFullAuthBundle\Entity\User;

use App\Core\DatabaseSchema;
use App\Serializer\SerializerGroups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use SymfonyFullAuthBundle\Entity\Traits\DatetimeTrait;
use SymfonyFullAuthBundle\Entity\Traits\PrimaryKeyTrait;
use SymfonyFullAuthBundle\Repository\User\UserAccountDetailRepository;


#[
    ORM\Entity(repositoryClass: UserAccountDetailRepository::class),
    ORM\Table(name: 'user_account_details'),
    ORM\HasLifecycleCallbacks
]
class UserAccountDetail
{
    use PrimaryKeyTrait, DatetimeTrait;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options: ["default" => 1])]
    #[Groups(SerializerGroups::PUBLIC)]
    private bool $realEmail = true;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options: ["default" => 0])]
    #[Groups(SerializerGroups::PUBLIC)]
    private bool $emailVerified = false;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options: ["default" => 1])]
    #[Groups(SerializerGroups::PUBLIC)]
    private bool $realPassword = true;



    /** Relation */
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'accountDetail')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: "id", onDelete: "CASCADE")]
    #[Groups(SerializerGroups::USER)]
    protected User $user;

    public function isRealEmail(): ?bool
    {
        return $this->realEmail;
    }

    public function setRealEmail(bool $realEmail): static
    {
        $this->realEmail = $realEmail;

        return $this;
    }

    public function isEmailVerified(): ?bool
    {
        return $this->emailVerified;
    }

    public function setEmailVerified(bool $emailVerified): static
    {
        $this->emailVerified = $emailVerified;

        return $this;
    }

    public function isRealPassword(): ?bool
    {
        return $this->realPassword;
    }

    public function setRealPassword(bool $realPassword): static
    {
        $this->realPassword = $realPassword;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }


}
