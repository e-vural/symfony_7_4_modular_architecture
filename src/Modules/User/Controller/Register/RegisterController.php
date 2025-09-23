<?php

namespace App\Modules\User\Controller\Register;


use App\Modules\User\Attribute\UserRoutePrefix;
use App\Modules\User\Entity\User;
use App\Modules\User\Form\Register\RegisterForm;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[
    OA\Post(tags: ["User"])
]
#[Security(name: null)]
#[UserRoutePrefix()]
class RegisterController extends AbstractController
{
    /**
     * Register process
     */
    #[Route('/register', name: 'register', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'Member registration payload',
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: RegisterForm::class),
//            example: [
//                'user' => [
//                    'identifier' => 'emre.vural@kodpit.com',
//                    'password' => 'Password1!'
//                ]
//            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of a user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class))
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Member Register Success"
    )]
    #[OA\Response(
        response: 400,
        description: "Member Register Problem"
    )]
    public function register(Request $request, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory): JsonResponse
    {



//        try {


        $payload = $request->toArray();


        $form = $formFactory->create(RegisterForm::class);

//        dump($form);exit();

        $form->submit($payload);

        if ($form->isValid()) {
            $entityManager->flush();
        }


        return new JsonResponse(["message" => "Success"], Response::HTTP_OK);
//        } catch (\Exception $e) {
//        dd($e->getMessage());
//        }
    }

}
