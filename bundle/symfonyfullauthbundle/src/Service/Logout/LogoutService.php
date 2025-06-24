<?php

namespace SymfonyFullAuthBundle\Service\Logout;

use AllowDynamicProperties;
use App\Entity\MobileDevice\MobileDevice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use SymfonyFullAuthBundle\Controller\View\Auth\LoginFailedException;
use SymfonyFullAuthBundle\Entity\User\User;

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
