<?php

namespace SymfonyFullAuthBundle\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class FullAuthBundleUserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user) : void
    {
//        if (!$user instanceof AppUser) {
//            return;
//        }
//
//        // user is deleted, show a generic Account Not Found message.
//        if ($user->isDeleted()) {
//            throw new AccountDeletedException('...');
//        }
    }

    public function checkPostAuth(UserInterface $user) : void
    {
//        if (!$user instanceof AppUser) {
//            return;
//        }
//
//        // user account is expired, the user may be notified
//        if ($user->isExpired()) {
//            throw new AccountExpiredException('...');
//        }
    }
}
