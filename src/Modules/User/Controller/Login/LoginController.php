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
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[
    OA\Tag(
        name: "User",
        description: "Kullanıcı işlemleri için API endpoint'leri"
    ),
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
            required: ["identifier", "password"],
            example: [
                'identifier' => 'emre.vural@kodpit.com',
                'password' => 'Password1!'
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Başarılı giriş - JWT token bilgileri",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "token",
                    description: "JWT access token",
                    type: "string",
                    example: "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
                ),
                new OA\Property(
                    property: "refreshToken",
                    description: "JWT refresh token",
                    type: "string",
                    example: "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
                ),
                new OA\Property(
                    property: "refreshTokenExpiration",
                    description: "Token'ın geçerlilik süresi (saniye)",
                    type: "integer",
                    example: 3600
                )
            ]
        )
    )]

    public function login(Request $request, JsonLoginService $jsonLoginService): JsonResponse
    {
        $payload = $request->toArray();

////        $reuslts = $validator->validate(LoginForm::class);
////        dd($reuslts);
        $form = $this->createForm(LoginForm::class);
////       $error = $form->get("password")->getConfig()->getOption('constraints');
//        foreach ($form->all() as  $key => $item) {
//            $constraints = $item->getConfig()->getOption("constraints");
//            dump($constraints);
//        }
//       dd($form->all());
        $form->submit($payload);

        if ($form->isSubmitted() && $form->isValid()) {

            $tokens = $jsonLoginService->login($form->get(UserIdentifierFormType::CHILD_NAME)->getData(), $form->get("password")->getData());
            return new JsonResponse($tokens);

        }
        return new JsonResponse(["error" => "Something went wrong"], 400);


    }

}
