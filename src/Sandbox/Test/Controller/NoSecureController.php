<?php

declare(strict_types=1);

namespace App\Sandbox\Test\Controller;

use App\Security\JWTManuelCheckerService;
use App\Shared\Controller\BaseAbstractApiController;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/no-secure')]
#[Security(name: null)]
#[OA\Tag(name: "Test",x: ['priority' => 999])]
class NoSecureController extends BaseAbstractApiController
{

    #[Route('',methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: "Token Object"
    )]
    public function index(JWTManuelCheckerService $JWTManuelChecker): Response
    {


        return $this->jsonResponse(array("logged_user_identifier" => $this->getUser()?->getUserIdentifier()));

    }
}
