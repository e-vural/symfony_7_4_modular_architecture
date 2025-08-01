<?php

namespace App\Controller\Api\Auth\Login;

use App\Controller\Api\Auth\AbstractAuthController;
use App\Form\Auth\Login\LoginForm;
use App\Service\Auth\Login\JsonLoginService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[
    OA\Post(tags: ["Auth"]),
]
#[Security(name: null)]
#[Route("/auth")]
class LoginController extends AbstractAuthController
{
    /**
     * Login Process
     */
    #[Route('/login', name: 'login', methods: ['POST'])]
    #[OA\RequestBody(
        description: "Member login payload",
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: LoginForm::class),
            example: [
                'identifier' => 'emre.vural@kodpit.com',
                'password' => 'Password1!'

            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Token Object"
    )]
    #[OA\Response(
        response: 400,
        description: "String"
    )]
    public function login(Request $request, JsonLoginService $jsonLoginService): JsonResponse
    {
        $payload = $request->toArray();
        $tokens = $jsonLoginService->login($payload["identifier"], $payload["password"]);
        return new JsonResponse($tokens);

    }

}
