<?php

declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\HttpResponses\ApiResponse;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[
    OA\Get(tags: ["Test"]),
    Security(name: "BearerAuth")
]
class TestController extends AbstractController
{
    #[Route('/test',methods: ['GET'])]
    #[OA\Response(
        response: 201,
        description: "Change password Success"
    )]
    public function index(): Response
    {
        $apiResponse = new ApiResponse();
        $json = 'naber';
        $message = 'This will "break" the JSON';
        $extraData = [];
        $status = 200;

        return  $apiResponse->jsonResponse($json, $message, $extraData, $status);

//        $data = json_decode($response->getContent(), true);
//
//       dd($data);
    }
}
