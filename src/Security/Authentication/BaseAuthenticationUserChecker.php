<?php

namespace App\Security\Authentication;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsAlias(id: "authentication_user_checker")]
class BaseAuthenticationUserChecker implements UserCheckerInterface
{

    /**
     *
     * JWT ile istek atarken çalışır
     * @param UserInterface $user
     * @return void
     */
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

    /**
     *
     * JWT ile istek biterken çalışır.
     * @param UserInterface $user
     * @return void
     */
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
