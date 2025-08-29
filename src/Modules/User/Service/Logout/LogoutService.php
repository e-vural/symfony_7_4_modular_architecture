<?php

namespace App\Modules\User\Service\Logout;

use AllowDynamicProperties;
use App\Modules\User\Entity\User;
use App\Service\Auth\Logout\MobileDevice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;


#[AllowDynamicProperties] class LogoutService
{

    private ?User $user;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {

        $this->user = $security->getUser();

        if (!$this->user) {
            throw new UserNotFoundException("User not found");
        }
        $this->entityManager = $entityManager;
    }

    public function logout(?MobileDevice $mobileDevice = null)
    {

        if ($mobileDevice) {
            $this->logoutMobileDevice($mobileDevice);
        } else {
            /** @var MobileDevice[] $mobileDevices */
            $mobileDevices = $this->user->getMobileDevices();


            foreach ($mobileDevices as $mobileDevice) {

                $this->logoutMobileDevice($mobileDevice);

            }
        }

        $this->entityManager->flush();

    }

    private function logoutMobileDevice(MobileDevice $mobileDevice)
    {

        $mobileDevice->setUser(null);
        $mobileDevice->setLogin(false);
//        $mobileDevice->setPushToken(false);

    }

}
