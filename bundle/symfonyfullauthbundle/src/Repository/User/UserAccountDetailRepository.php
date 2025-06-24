<?php

namespace SymfonyFullAuthBundle\Repository\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SymfonyFullAuthBundle\Entity\User\UserAccountDetail;

/**
 * @extends ServiceEntityRepository<UserAccountDetail>
 */
class UserAccountDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAccountDetail::class);
    }
}
