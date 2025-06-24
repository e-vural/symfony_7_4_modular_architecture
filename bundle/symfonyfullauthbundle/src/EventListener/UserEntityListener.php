<?php

namespace SymfonyFullAuthBundle\EventListener;


use AllowDynamicProperties;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyFullAuthBundle\Entity\User\User;

#[AllowDynamicProperties]
#[AsEntityListener(event: Events::prePersist, method: "prePersist", entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: "preUpdate", entity: User::class)]
//#[AsEntityListener(event: Events::postPersist, method: "postPersist", entity: User::class)]
//#[AsEntityListener(event: Events::preFlush, method: "preFlush", entity: User::class)]
class UserEntityListener
{

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }



    public function prePersist(User $user, PrePersistEventArgs $event): void
    {
//        dd($user);
        if ($user->getPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
        }
//        dd($hashedPassword);

    }


    public function preUpdate(User $user, PreUpdateEventArgs $event): void
    {
//        $entity = $args->getObject();

        $passwordIsChanged = $event->hasChangedField("password");
        if ($passwordIsChanged) {
            $this->hashPassword($user);
        }
//        dd($passwordIsChanged);
//        $this->hashPassword($user);
//        dd($user);
        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible


//        $entityManager = $args->getObjectManager();
        // ... do something with the Product entity
    }

    private function hashPassword(User $user)
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

    }

}
