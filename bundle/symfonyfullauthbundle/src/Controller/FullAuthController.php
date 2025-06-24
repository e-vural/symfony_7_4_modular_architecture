<?php

declare(strict_types=1);

namespace SymfonyFullAuthBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyFullAuthBundle\Form\Register\RegisterForm;

class FullAuthController extends AbstractController
{

//    #[Route('/full-auth', name: 'full_auth', methods: ["POST"])]
    #[OA\RequestBody(
        description: "Member registration payload",
        required: true,
        content: new Model(type: RegisterForm::class)
    )]
    #[OA\Response(
        response: 201,
        description: "Member created successfully"
    )]
    #[OA\Response(
        response: 400,
        description: "Validation error"
    )]
    public function index(JWTTokenManagerInterface $JWTToken,EntityManagerInterface $entityManager, Request $request, FormFactoryInterface $formFactory): Response
    {
        try {
            $payload = $request->toArray();
            $form = $formFactory->create(RegisterForm::class);
            $form->submit($payload);

            $entityManager->flush();
            return new JsonResponse(["message" => "Success"], Response::HTTP_OK);
        }catch (\Exception $e){
            return new JsonResponse(["message" => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
//        $user = new Member();
//
//        $user->setEmail("hasan.kacar2@kodpit.com");
//        $user->setRoles(array("ROLE_ADMIN"));
//        $user->setPassword("123");
//        $entityManager->persist($user);
//
//
//        $profile = new Profile();
//        $profile->setName("Hasan2");
//        $profile->setSurname("Kacar2");
//        $profile->setFullName();
//        $profile->setUser($user);
//
//        $entityManager->persist($profile);
//
//        $entityManager->flush();
//
//
//        $user = $entityManager->getRepository(Member::class)->find(3);
//        $token = $JWTToken->create($user);
//        dd($token);
//        return $this->render('@SymfonyFullAuth/hasan.html.twig');
    }
}
