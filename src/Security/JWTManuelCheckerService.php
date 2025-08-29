<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTManuelCheckerService
{

    const POST_JWT_PARAMETER = "authorization";
    public function __construct(private readonly RequestStack $requestStack, private readonly TokenExtractorInterface $tokenExtractor, private readonly JWTTokenManagerInterface $JWTTokenManager)
    {

    }

    public function checkFormRequest()
    {
        $request = $this->requestStack->getCurrentRequest();

        $jwt = $this->tokenExtractor->extract($request);

        if (!$jwt) {

            throw new JWTDecodeFailureException("jwt_not_found","You need Authorize");
        }

        try {
            $parsedJWT = $this->JWTTokenManager->parse($jwt);

        } catch (JWTDecodeFailureException $exception) {
            if ($exception->getReason() == JWTDecodeFailureException::INVALID_TOKEN) {
                throw new JWTDecodeFailureException($exception->getReason(),"Your credentials are invalid");
            }

            if ($exception->getReason() == JWTDecodeFailureException::EXPIRED_TOKEN) {

                throw new JWTDecodeFailureException($exception->getReason(),"Your credentials are expired");


            }
        }

        return ["parsedJWT" => $parsedJWT,"jwt" => $jwt,"user" => ""];

    }
    public function checkFromPostParameter($paramName = self::POST_JWT_PARAMETER)
    {
        $request = $this->requestStack->getCurrentRequest();

        $jwt = $request->request->get($paramName);

        if (!$jwt) {

            throw new JWTDecodeFailureException("jwt_not_found","You need Authorize");
        }

        try {
            $parsedJWT = $this->JWTTokenManager->parse($jwt);

        } catch (JWTDecodeFailureException $exception) {
            if ($exception->getReason() == JWTDecodeFailureException::INVALID_TOKEN) {
                throw new JWTDecodeFailureException($exception->getReason(),"Your credentials are invalid");
            }

            if ($exception->getReason() == JWTDecodeFailureException::EXPIRED_TOKEN) {

                throw new JWTDecodeFailureException($exception->getReason(),"Your credentials are expired");


            }
        }

        return ["parsedJWT" => $parsedJWT,"jwt" => $jwt,"user" => ""];

    }


}
