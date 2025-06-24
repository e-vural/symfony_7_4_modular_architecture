<?php

namespace SymfonyFullAuthBundle\Controller\Api\Register;

use App\Serializer\SerializerGroups;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyFullAuthBundle\Entity\Profile\Profile;
use SymfonyFullAuthBundle\Entity\User\User;
use SymfonyFullAuthBundle\Form\FormException;
use SymfonyFullAuthBundle\Form\Register\RegisterForm;


#[
    OA\Post(tags: ["Register"]),
    OA\Get(tags: ["Register"])
]
class RegisterController extends AbstractController
{
    /**
     * Register process
     */
    #[Route('/register', name: 'register', methods: ['POST'])]
    #[OA\RequestBody(
        description: "Member registration payload",
        required: true,
        content: new Model(type: RegisterForm::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of a user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: [SerializerGroups::PUBLIC]))
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
        try {
            $payload = $request->toArray();
            $form = $formFactory->create(RegisterForm::class);

//            dd($form->getConfig());
//            $form->handleRequest($request);
            $form->submit($payload);

//            dd(1);
//            /** @var FormError $getError */
//            foreach ($form->getErrors(true) as $getError) {
//                dump($getError->getMessage(),$getError->getOrigin()->getName());
//            }
//            dd($form->isValid(),$form->getErrors(true));
            if($form->isValid()){
                $entityManager->flush();
            }
            return new JsonResponse(["message" => "Success"], Response::HTTP_OK);
        }catch (FormException $e){


            return new JsonResponse(["message" => $e->getMessage(),"errors" => $e->getErrors()], Response::HTTP_FORBIDDEN);
        }
    }

}
