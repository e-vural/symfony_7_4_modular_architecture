<?php

declare(strict_types=1);

namespace App\Sandbox\Test\Controller;

use App\Shared\Controller\BaseAbstractApiController;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secured')]
#[OA\Tag(name: "Test")]
class SecuredController extends BaseAbstractApiController
{

    #[Route('',methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: "Token Object"
    )]
    public function index(): Response
    {



//        return $this->json(array("test" => "yavaş version"));
        return $this->jsonResponse(array("logged_user_identifier" => $this->getUser()->getUserIdentifier()));
    }
}
