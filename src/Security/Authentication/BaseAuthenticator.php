<?php

namespace App\Security\Authentication;

use AllowDynamicProperties;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

//class CustomApiAuthenticator extends AbstractAuthenticator

#[AllowDynamicProperties] class BaseAuthenticator extends JWTAuthenticator
{
//    public function __construct(JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $eventDispatcher, TokenExtractorInterface $tokenExtractor, UserProviderInterface $userProvider, TranslatorInterface $translator = null, HttpClientInterface $httpClient)
//    {
//        parent::__construct($jwtManager, $eventDispatcher, $tokenExtractor, $userProvider, $translator);
//        $this->httpClient = $httpClient;
//    }

    /**
     *IF JWT NOT SEND FROM CLIENT THAT FUNCTION WILL TRIGGER
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {


//        dd(1);
        parent::start($request, $authException); //

        return new JsonResponse(["message" => "You need to authorize"], Response::HTTP_UNAUTHORIZED);
    }

//    public function authenticate(Request $request): Passport
//    {
//        try {
//            return parent::authenticate($request);
//        }catch (ExpiredTokenException $exception){
//            $token = $this->getTokenExtractor()->extract($request);
//            $payload = $this->jwsProvider->load($token);
//            dd($payload);
//        }
//    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {


        // TODO Bu response ortak kullanılacak olan reposne generator classından geçmelidir.
        if ($exception instanceof ExpiredTokenException) {
            return new JsonResponse(["message" => "JWT Token Expired"], Response::HTTP_UNAUTHORIZED);
        } elseif ($exception instanceof InvalidTokenException) {
            return new JsonResponse(["message" => "JWT Token Invalid"], Response::HTTP_NOT_ACCEPTABLE);
        } else {
            return new JsonResponse(["message" => "JWT Token Problem"], Response::HTTP_FORBIDDEN);
        }
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {

        $token = new BaseAuthenticationToken($passport->getUser(), $firewallName, $passport->getUser()->getRoles(), $passport->getAttribute('token'));
        return $token;
    }
}
