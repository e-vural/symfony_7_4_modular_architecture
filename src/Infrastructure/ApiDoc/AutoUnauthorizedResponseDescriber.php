<?php

namespace App\Infrastructure\ApiDoc;

use App\Modules\User\Entity\User;
use App\Shared\Http\BaseJsonResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Describer\DescriberInterface;

//use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\RouteDescriber\RouteArgumentDescriber\RouteArgumentDescriberInterface;
use OpenApi\Annotations as OA;
use OpenApi\Attributes as OAA;
use OpenApi\Generator;
use OpenApi\Processors\OperationId;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class AutoUnauthorizedResponseDescriber implements RouteArgumentDescriberInterface
{

    private $paths = [];

    private $unauthorizedResponse;
//    private $forbiddenResponse;
    public function __construct()
    {
        $this->unauthorizedResponse = new OAA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: '{"message" : "Token Problem ($reason)"}',
//            content: new OAA\MediaType(
//                mediaType: "application/json",
//                schema: new OAA\Schema(
//                    schema: "CP_Unauthorized",
//                    example: ["message" => "401 Unauthorized"],
//
//                )
//
//            )
        );
//        $this->forbiddenResponse = new OAA\Response(
//            response: Response::HTTP_FORBIDDEN,
//            description: "TE",
////            content: new OAA\MediaType(
////                mediaType: "application/json",
////                schema: new OAA\Schema(
////                    schema: "CP_Forbidden",
////                    example: ["message" => "403 Forbidden"],
////                ),
////            )
//        );
    }

    public function describe(ArgumentMetadata $argumentMetadata, OA\Operation $operation): void
    {


            $isSecured = $this->isOperationSecured($operation);
            if ($isSecured) {
                $this->addUnauthorizedResponse($operation);
            }

    }

    /**
     * Operation'ın secured olup olmadığını kontrol eder
     */
    private function isOperationSecured(OA\Operation $operation): bool
    {
        // Security null ise secured değil
        if ($operation->security === null) {
            return false;
        }

        // Security boş array ise secured değil
        if (is_array($operation->security) && count($operation->security) === 0) {
            return false;
        }

//        // Security array'de Bearer token varsa secured
//        if (is_array($operation->security)) {
//            foreach ($operation->security as $securityRequirement) {
//                if ($this->hasBearerSecurity($securityRequirement)) {
//                    return true;
//                }
//            }
//        }

        return true;
    }

    /**
     * Security requirement'da Bearer token olup olmadığını kontrol eder
     */
    private function hasBearerSecurity($securityRequirement): bool
    {
        if (is_array($securityRequirement)) {
            return array_key_exists('Bearer', $securityRequirement);
        }

        // Object olarak gelirse property'leri kontrol et
        if (is_object($securityRequirement) && property_exists($securityRequirement, 'security')) {
            $security = $securityRequirement->security;
            if (is_array($security)) {
                return array_key_exists('Bearer', $security);
            }
        }

        return false;
    }


    /**
     * Operation'a 401 Unauthorized response ekler
     */
    private function addUnauthorizedResponse(OA\Operation $operation): void
    {
        // Zaten 401 response varsa ekleme
        if ($this->hasResponse($operation, Response::HTTP_UNAUTHORIZED) || $this->hasResponse($operation, Response::HTTP_FORBIDDEN)) {
            return;
        }

        // Response'u operation'a ekle
        if (!isset($operation->responses)) {
            $operation->responses = [];
        }

        $operation->responses[] = $this->unauthorizedResponse;
//        $operation->responses[] = $this->forbiddenResponse;


    }


    /**
     * Operation'da belirli bir response code'unun olup olmadığını kontrol eder
     */
    private function hasResponse(OA\Operation $operation, int $code): bool
    {
        if (!isset($operation->responses) || !is_array($operation->responses)) {
            return false;
        }

        foreach ($operation->responses as $response) {
            if (is_object($response) && property_exists($response, 'response')) {
                if ((string)$response->response === (string)$code) {
                    return true;
                }
            }
        }

        return false;
    }
}
