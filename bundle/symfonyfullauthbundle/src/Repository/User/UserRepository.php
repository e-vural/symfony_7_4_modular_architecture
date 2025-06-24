<?php

namespace SymfonyFullAuthBundle\Repository\User;

use App\Core\Roles\UserRole;
use App\Entity\Workspace\Workspace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use SymfonyFullAuthBundle\Entity\User\User;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function getUserAfterLogin(string $email)
    {
        $qb = $this->createQueryBuilder("user")->select("partial user.{id,email}")
            ->leftJoin("user.profile","profile")->addSelect("partial profile.{id,name,surname,fullName}");
        $qb->where("user.email = :email")->setParameter("email", (string)$email);
        return $qb->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);
    }



    public function isClientUser(User $user): bool
    {
        $clientUser = $this->createQueryBuilder("user")
            ->where("JSON_GET_TEXT(user.roles,1) = :role ")->setParameter('role', UserRole::ROLE_CLIENT)
            ->andWhere('user.id = :userId')->setParameter('userId', $user)
            ->getQuery()
            ->getOneOrNullResult();

        if ($clientUser){
            return true;
        }

        return false;
    }
}
