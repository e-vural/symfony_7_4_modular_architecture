<?php

namespace SymfonyFullAuthBundle\Controller\Api\ChangePassword;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyFullAuthBundle\Form\ChangePassword\ChangePasswordForm;


#[
    OA\Post(tags: ["Change Password"]),
]
class ChangePasswordController extends AbstractController
{
    #[Route('/change-password', name: 'change_password', methods: ["POST"])]
//    #[OA\RequestBody(
//        description: "Member change password payload",
//        required: true,
//        content: new Model(type: ChangePasswordType::class)
//    )]
//    #[OA\Response(
//        response: 201,
//        description: "Change password Success"
//    )]
//    #[OA\Response(
//        response: 400,
//        description: "Change password Problem"
//    )]
    public function changePassword(Request $request, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager)
    {
        try {
            $user = $this->getUser();
            $payload = $request->toArray();
            $payload["user"] = $user;
            $form = $formFactory->create(ChangePasswordForm::class);
            $form->submit($payload);

            $entityManager->flush();
            return new JsonResponse(["message" => "success"], Response::HTTP_FORBIDDEN);
        }catch (Exception $e){
            return new JsonResponse(["message" => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}
