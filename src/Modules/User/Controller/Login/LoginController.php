<?php

namespace App\Modules\User\Controller\Login;

use App\Modules\User\Attribute\UserRoutePrefix;
use App\Modules\User\Form\Login\LoginForm;
use App\Modules\User\FormType\User\UserIdentifierFormType;
use App\Modules\User\Service\Login\JsonLoginService;
use App\Shared\Controller\BaseAbstractApiController;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[
    OA\Tag(
        name: "User",
        description: "Kullanıcı işlemleri için API endpoint'leri"
    ),
    OA\Post(
        path: "/login",
        summary: "Kullanıcı Girişi",
        description: "Kullanıcı kimlik bilgileri ile sisteme giriş yapar ve JWT token döner",
        tags: ["User"],
        operationId: "userLogin"
    )
]
#[Security(name: null)]
#[UserRoutePrefix()]
class LoginController extends BaseAbstractApiController
{
    /**
     * Login Process
     */
    #[Route('/login', name: 'login', methods: ['POST'])]
    #[OA\RequestBody(
        description: "Kullanıcı giriş bilgileri",
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: LoginForm::class),
            example: [
                'identifier' => 'emre.vural@kodpit.com',
                'password' => 'Password1!'
            ],
            required: ["identifier", "password"]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Başarılı giriş - JWT token bilgileri",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "token",
                    type: "string",
                    description: "JWT access token",
                    example: "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
                ),
                new OA\Property(
                    property: "refreshToken",
                    type: "string",
                    description: "JWT refresh token",
                    example: "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
                ),
                new OA\Property(
                    property: "refreshTokenExpiration",
                    type: "integer",
                    description: "Token'ın geçerlilik süresi (saniye)",
                    example: 3600
                )
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Hatalı istek - Form validasyon hatası",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "Hata mesajı",
                    example: "Something went wrong"
                ),
                new OA\Property(
                    property: "errors",
                    type: "object",
                    description: "Form validasyon hataları",
                    example: [
                        "identifier" => ["Bu alan zorunludur"],
                        "password" => ["Bu alan zorunludur"]
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: "Kimlik doğrulama hatası",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "Kimlik doğrulama hatası mesajı",
                    example: "Invalid credentials"
                )
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: "Sunucu hatası",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "Sunucu hatası mesajı",
                    example: "Internal server error"
                )
            ]
        )
    )]
    public function login(Request $request, JsonLoginService $jsonLoginService): JsonResponse
    {
        $payload = $request->toArray();

        $form = $this->createForm(LoginForm::class);

        $form->submit($payload);

        if($form->isSubmitted() && $form->isValid()) {

            $tokens = $jsonLoginService->login($form->get(UserIdentifierFormType::CHILD_NAME)->getData(), $form->get("password")->getData());
            return new JsonResponse($tokens);

        }
        return new JsonResponse(["error" => "Something went wrong"], 400);


    }

}
