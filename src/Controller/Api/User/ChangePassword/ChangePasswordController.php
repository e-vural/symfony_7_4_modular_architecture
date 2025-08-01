<?php

namespace App\Controller\Api\User\ChangePassword;

use App\Form\User\ChangePassword\ChangePasswordForm;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[
    OA\Post(tags: ["User"]),
]
#[Route("/user")]
class ChangePasswordController extends AbstractController
{
    #[Route('/change-password', name: 'change_password', methods: ["POST"])]
    #[OA\RequestBody(
        description: "Member change password payload",
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: ChangePasswordForm::class),
            example: [
                'oldPassword' => 'Password1!',
                'newPassword' => [
                    'first' => "Password2!",
                    'second' => "Password2!",
                ],
            ]

        )
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

        $payload = $request->toArray();

//        $payload["user"] = $this->getUser();
        $form = $formFactory->create(ChangePasswordForm::class);
        $form->submit($payload);

        if($form->isValid()){
            $user = $this->getUser();
            $user->setPassword($form->get("newPassword")->getData());
        }

        $entityManager->flush();
        return new JsonResponse(["message" => "success"], Response::HTTP_OK);

    }
}
