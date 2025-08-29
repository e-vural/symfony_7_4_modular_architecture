<?php

namespace App\Shared\Controller;



use App\Shared\Http\BaseJsonResponse;
use App\Shared\Serializer\SymfonySerializer;
use App\Shared\Service\LoggedUserService;
use App\Shared\Traits\Symfony\EntityManagerProviderTrait;
use App\Shared\Traits\Symfony\FormFactoryInterfaceProviderTrait;
use App\Shared\Traits\Symfony\RequestStackProviderTrait;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

abstract class BaseAbstractApiController implements ServiceSubscriberInterface
{
    use ServiceMethodsSubscriberTrait, EntityManagerProviderTrait, FormFactoryInterfaceProviderTrait, RequestStackProviderTrait;

    private array $payload = [];
//    private ?MobileDevice $mobileDevice = null;

//    public function __construct(private readonly ApiResponse $apiResponse)
//    {
//    }

    public static function getSubscribedServices(): array
    {
        return [
            'security.authorization_checker' => '?' . AuthorizationCheckerInterface::class,
            FormFactoryInterface::class,
            'security.token_storage' => '?' . TokenStorageInterface::class,
            LoggedUserService::class,
            'parameter_bag' => '?' . ContainerBagInterface::class,
            BaseJsonResponse::class,
            EntityManagerInterface::class,
//            MessageServiceProvider::class,
            SymfonySerializer::class,
            RequestStack::class
        ];
    }


    public function getPayload(): array
    {

        if (!$this->payload) {
            $request = $this->getRequestStack()->getCurrentRequest();

            /** If we don't send any body data on the request(For Web side) system return an error. So, I added this control */
            if (!empty($request->getContent())) {
                $this->payload = $request->toArray();
            }

            if (count($request->request->all()) > 0){
                $this->payload = $request->request->all();
            }
        }

        return $this->payload;
    }

    public function getMobileDevice()
    {

        if ($this->mobileDevice) {
            return $this->mobileDevice;
        }

//        $payload = $this->getPayload();
//        $deviceId = @$payload["deviceId"];
//
//        if (!$deviceId) {
//            $request = $this->getRequestStack()->getCurrentRequest();
//            $deviceId = $request->headers->get('deviceId');
//        }
//
//        if ($deviceId) {
//            $this->mobileDevice = $this->getEntityManager()->getRepository(MobileDevice::class)->findOneBy(array("deviceId" => $deviceId));
//        }

        return $this->mobileDevice;
    }


    public function jsonResponse(mixed $data , string $message = null, $extraData = [], int $status = 200): JsonResponse
    {
        /** @var BaseJsonResponse $apiResponse */
        $apiResponse = $this->container->get(BaseJsonResponse::class);

//        $request = $this->getRequestStack()->getCurrentRequest();

//        if (@$this->getPayload()["deviceId"]){
//        $apiResponse->setMobileDevice($this->getMobileDevice());
//        }else{
//            dd("device id yok",$this->getPayload());
//        }
        return $apiResponse->jsonResponse($data, $message, $extraData, $status);
    }

//    /**
//     * @throws ContainerExceptionInterface
//     * @throws NotFoundExceptionInterface
//     */
//    public function apiErrorResponse($message = "Error", $extraData = [], $errorCode = Response::HTTP_FORBIDDEN): JsonResponse
//    {
//        return $this->apiResponse("", $message, $extraData, $errorCode);
//    }


    /**
     * Checks if the attribute is granted against the current authentication token and optionally supplied subject.
     *
     * @throws \LogicException
     */
    protected function isGranted(mixed $attribute, mixed $subject = null): bool
    {
        if (!$this->container->has('security.authorization_checker')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        return $this->container->get('security.authorization_checker')->isGranted($attribute, $subject);
    }

    /**
     * Throws an exception unless the attribute is granted against the current authentication token and optionally
     * supplied subject.
     *
     * @throws AccessDeniedException
     */
    protected function denyAccessUnlessGranted(mixed $attribute, mixed $subject = null, string $message = 'Access Denied.'): void
    {
        if (!$this->isGranted($attribute, $subject)) {
            $exception = $this->createAccessDeniedException($message);
            $exception->setAttributes([$attribute]);
            $exception->setSubject($subject);

            throw $exception;
        }
    }

    /**
     * Returns an AccessDeniedException.
     *
     * This will result in a 403 response code. Usage example:
     *
     *     throw $this->createAccessDeniedException('Unable to access this page!');
     *
     * @throws \LogicException If the Security component is not available
     */
    protected function createAccessDeniedException(string $message = 'Access Denied.', ?\Throwable $previous = null): AccessDeniedException
    {
        if (!class_exists(AccessDeniedException::class)) {
            throw new \LogicException('You cannot use the "createAccessDeniedException" method if the Security component is not available. Try running "composer require symfony/security-bundle".');
        }

        return new AccessDeniedException($message, $previous);
    }


    /**
     * Creates and returns a Form instance from the type of the form.
     * @param $type string
     * @param $data mixed
     * @param array<string, mixed> $options #FormOption
     */
    protected function createForm(string $type, mixed $data = null, array $options = []): FormInterface
    {

        return $this->getFormFactory()->create($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance.
     */
    protected function createFormBuilder(mixed $data = null, array $options = []): FormBuilderInterface
    {
        return $this->getFormFactory()->createBuilder(FormType::class, $data, $options);
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    protected function getUser(): ?UserInterface
    {

//        //TODO kendi servisini yap oradan çağır
//        if (!$this->container->has('security.token_storage')) {
//            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
//        }
//
//        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
//            return null;
//        }

        return  $this->container->get(LoggedUserService::class)->getUser();
    }



    public function getSymfonySerializer(): SymfonySerializer
    {
        return $this->container->get(SymfonySerializer::class);
    }



}
