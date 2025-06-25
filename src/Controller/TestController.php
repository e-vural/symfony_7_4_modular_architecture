<?php

declare(strict_types=1);

namespace App\Controller;

use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[
    OA\Get(tags: ["TEST"]),
    Security(name: "BearerAuth")
]
class TestController extends AbstractController
{
    #[Route('/test')]
    #[OA\Get()]
    public function index(): Response
    {
       dd(1);
    }
}
