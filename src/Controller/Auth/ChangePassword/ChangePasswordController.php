<?php

namespace App\Controller\Auth\ChangePassword;

use App\Form\Auth\ChangePassword\ChangePasswordForm;
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


#[
    OA\Post(tags: ["Auth"]),
]
#[Route("/auth")]
class ChangePasswordController extends AbstractController
{
    #[Route('/change-password', name: 'change_password', methods: ["POST"])]
//    #[OA\Parameter(
//        name: '_locale',
//        in: 'path',
//        description: 'The field used to order rewards',
//        schema: new OA\Schema(type: 'string',default: "tr", enum: ['tr', 'en', 'de', 'fr'])
//    )]
    #[OA\RequestBody(
        description: "Member change password payload",
        required: true,
        content: new Model(type: ChangePasswordForm::class)
    )]
    #[OA\Response(
        response: 201,
        description: "Change password Success"
    )]
    #[OA\Response(
        response: 400,
        description: "Change password Problem"
    )]
    public function changePassword(Request $request, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager)
    {
        try {
            $user = $this->getUser();
            $payload = $request->toArray();
            $payload["user"] = $user;
            $form = $formFactory->create(ChangePasswordForm::class);
            $form->submit($payload);

            $entityManager->flush();
            return new JsonResponse(["message" => "success"], Response::HTTP_OK);
        }catch (Exception $e){
            return new JsonResponse(["message" => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}
