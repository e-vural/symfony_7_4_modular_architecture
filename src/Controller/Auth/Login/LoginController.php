<?php

namespace App\Controller\Auth\Login;

use App\Controller\Auth\AbstractAuthController;
use App\Form\Auth\Login\LoginForm;
use Exception;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use SymfonyFullAuthBundle\Service\Login\JsonLoginService;


#[
    OA\Post(tags: ["Auth"]),
]
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
        content: new Model(type: LoginForm::class)
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

        try {
            // TODO Burası postmanden kendimiz için kullandığımız alan
            $payload = $request->toArray();
            $tokens = $jsonLoginService->login($payload["identifier"], $payload["password"]);
//            $loginCheckUrl = $this->generateUrl("api_login_check",[],UrlGeneratorInterface::ABSOLUTE_URL);
////            $loginCheckUrl = "http://php/api/login_check"; /** For docker route */
//            $response = $httpClient->request("POST",$loginCheckUrl,[
//                "json" => $payload
//            ]);
//            $tokens = $response->toArray();


            return new JsonResponse(["token" => $tokens]);

        }catch (Exception|LoginFailedException|TransportExceptionInterface|ServerExceptionInterface|RedirectionExceptionInterface|DecodingExceptionInterface|ClientExceptionInterface $e){

            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

}
